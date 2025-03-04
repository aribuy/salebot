<?php

namespace App\Repositories\Client;
use App\Traits\ImageTrait;
use App\Traits\ContactTrait;
use App\Traits\RepoResponse;
use App\Traits\TelegramTrait;
use App\Models\GroupSubscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TelegramSubscriberRepository
{
    use ContactTrait, ImageTrait, RepoResponse, TelegramTrait;
    private $model;

    public function __construct(
        GroupSubscriber $model,
    ) {
        $this->model = $model;
    }

    public function all($request)
    {
        return $this->model->latest()->withPermission()->paginate(setting('pagination'));
    }

    public function find($id)
    {
        return $this->model->withPermission()->find($id);
    }

    public function removeBlacklist($id)
    {
        try {
            DB::beginTransaction();

            // Retrieve the subscriber and check if it exists
            $subscriber = GroupSubscriber::findOrFail($id);
            if (!$subscriber) {
                throw new \Exception("Subscriber not found.");
            }

            // Log the subscriber details
            \Log::info('Subscriber details:', [$subscriber]);

            // Ensure the group_chat_id property exists
            if (!isset($subscriber->group_chat_id)) {
                throw new \Exception("Property [group_chat_id] does not exist on this subscriber instance.");
            }

            // Ensure you are accessing the authenticated user correctly
            $botToken = Auth::user()->client->telegramSetting->access_token ?? null;
            if (!$botToken) {
                throw new \Exception("Bot token not found.");
            }

            $method = 'unbanChatMember';
            $chatId = $subscriber->group_chat_id;
            $subscriberId = $subscriber->unique_id;

            $result = $this->banUnbanChatMember($botToken, $method, $chatId, $subscriberId);

            // Log the result for debugging
            \Log::info('Unban result:', [$result]);

            if (isset($result) && $result['result'] == true) {
                $subscriber->is_blacklist = 1;
                $subscriber->save();
            } else {
                return $this->formatResponse(
                    false,
                    $result['description'] ?? __('an_unexpected_error_occurred_please_try_again_later'),
                    'client.telegram.subscribers.index',
                    []
                );
            }
            DB::commit();

            return $this->formatResponse(true, __('successfully_removed_from_blacklist'), 'client.contacts.index', []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            // Log the error message
            \Log::error('Error in removeBlacklist: ' . $e->getMessage());

            DB::rollBack();

            return $this->formatResponse(
                false,
                __('an_unexpected_error_occurred_please_try_again_later'),
                'client.telegram.subscribers.index',
                []
            );
        }
    }



    public function addBlock($id)
    {
        try {
            DB::beginTransaction();

            // Retrieve the subscriber with its group
            $subscriber = GroupSubscriber::with('group')->where('id', $id)->firstOrFail();

            // Log the subscriber details for debugging
            \Log::info('Subscriber details:', [$subscriber]);

            // Ensure the group relationship is correctly loaded
            if (!$subscriber->relationLoaded('group') || !$subscriber->group) {
                throw new \Exception("Group not found for this subscriber.");
            }

            $group = $subscriber->group;

            // Ensure the group_id property exists
            if (!isset($group->group_id)) {
                throw new \Exception("Property [group_id] does not exist on the group instance.");
            }

            // Ensure the unique_id property exists
            if (!isset($subscriber->unique_id)) {
                throw new \Exception("Property [unique_id] does not exist on this subscriber instance.");
            }

            // Ensure you are accessing the authenticated user correctly
            $botToken = Auth::user()->client->telegramSetting->access_token ?? null;
            if (!$botToken) {
                throw new \Exception("Bot token not found.");
            }

            $method = 'banChatMember';
            $chatId = $group->group_id;
            $subscriberId = $subscriber->unique_id;

            // Log the parameters before making the API call
            \Log::info('Ban/Unban parameters:', [
                'botToken' => $botToken,
                'method' => $method,
                'chatId' => $chatId,
                'subscriberId' => $subscriberId
            ]);

            $result = $this->banUnbanChatMember($botToken, $method, $chatId, $subscriberId);
            // Log the result for debugging
            \Log::info('Ban/Unban result:', [$result]);

            if (isset($result) && $result['result'] == true) {
                $subscriber->is_blacklist = 1;
                $subscriber->save();
            } else {
                return $this->formatResponse(
                    false,
                    $result['description'] ?? __('an_unexpected_error_occurred_please_try_again_later'),
                    'client.telegram.subscribers.index',
                    []
                );
            }

            DB::commit();
            return $this->formatResponse(true, __('successfully_blacklisted'), 'client.contacts.index', []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            // Log the error message
            \Log::error('Error in addBlock: ' . $e->getMessage());

            DB::rollBack();

            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later'), 'client.contacts.index', []);
        }
    }





    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $subscriber = $this->model->find($id);
            if ($subscriber) {
                $subscriber->delete();
                DB::commit();
                return $this->formatResponse(true, __('deleted_successfully'), 'client.contacts.index', []);
            } else {
                return $this->formatResponse(false, 'Contact not found', 'client.contacts.index', []);
                throw new \Exception('Contact not found');
            }
        } catch (\Throwable $e) {
            dd($e->getMessage());
            DB::rollBack();
            return $this->formatResponse(false, $e->getMessage(), 'client.contacts.index', []);
        }
    }

    public function telegramSubscriberSync($id)
    {
        try {
            DB::beginTransaction();
            $subscriber = GroupSubscriber::find($id);
            if (!$subscriber) {
                throw new \Exception("Subscriber not found.");
            }
            $token = @Auth::user()->client->telegramSetting->access_token;
            $chatId = $subscriber->group_chat_id;
            $subscriberId = $subscriber->unique_id;
            $result = $this->syncSubscriber($token, $chatId, $subscriberId);
            if (isset($result) && !empty($result['result']['user'])) {
                $scopes = $this->extractScopes($result['result']);
                $user = $result['result']['user'];
                $subscriber->scopes = $scopes ?? [];
                $subscriber->name = $user['first_name'] ?? $subscriber->name;
                $subscriber->username = $user['username'] ?? $subscriber->username;
                $subscriber->is_bot = $user['is_bot'] ?? $subscriber->is_bot;
                $subscriber->update();
                DB::commit();
                return $this->formatResponse(
                    true,
                    __('subscriber_sync_successfully'),
                    'client.telegram.subscribers.index',
                    []
                );
            } else {
                // throw new \Exception("Failed to sync subscriber. Response: " . json_encode($result));
                return $this->formatResponse(
                    false,
                    $result['description'] ?? __('an_unexpected_error_occurred_please_try_again_later'),
                    'client.telegram.subscribers.index',
                    []
                );
            }
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            \Log::error('Error in telegramSubscriberSync: ' . $e->getMessage());
            return $this->formatResponse(
                false,
                __('an_unexpected_error_occurred_please_try_again_later'),
                'client.telegram.subscribers.index',
                []
            );
        }
    }
}
