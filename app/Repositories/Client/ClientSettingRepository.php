<?php

namespace App\Repositories\Client;

use App\Models\Client;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\Message;
use App\Models\BotGroup;
use App\Models\Campaign;
use App\Models\Template;
use Illuminate\Support\Str;
use App\Models\Conversation;
use App\Traits\RepoResponse;
use App\Models\ClientSetting;
use App\Traits\TelegramTrait;
use App\Traits\TemplateTrait;
use App\Models\GroupSubscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

class ClientSettingRepository
{
    use RepoResponse, TelegramTrait, TemplateTrait;

    private $model;

    private $botGroup;

    private $groupSubscriber;

    private $contact;

    private $conversation;

    private $campaign;

    private $message;

    private $client;

    private $template;

    public function __construct(
        ClientSetting $model,
        BotGroup $botGroup,
        GroupSubscriber $groupSubscriber,
        Contact $contact,
        Conversation $conversation,
        Campaign $campaign,
        Message $message,
        Client $client,
        Template $template,
    ) {
        $this->model           = $model;
        $this->botGroup        = $botGroup;
        $this->groupSubscriber = $groupSubscriber;
        $this->contact         = $contact;
        $this->conversation    = $conversation;
        $this->campaign        = $campaign;
        $this->message         = $message;
        $this->client         = $client;
        $this->template         = $template;
    }

    public function whatsAppSettingUpdate($request)
    {

        DB::beginTransaction();
        try {
            $is_connected = 0;
            $token_verified = 0;
            $scopes         = null;
            $accessToken  = $request->access_token;
            $url          = 'https://graph.facebook.com/debug_token?input_token=' . $accessToken . '&access_token=' . $accessToken;
            $ch           = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $response     = curl_exec($ch);
            $responseData = json_decode($response, true);

            if (isset($responseData['error'])) {
                return $this->formatResponse(false, $responseData['error']['message'], 'client.whatsapp.settings', []);
            } else {
                if (isset($responseData['data']['is_valid']) && $responseData['data']['is_valid'] === true) {
                    $is_connected = 1;
                    $token_verified = 1;
                    $scopes = $responseData['data']['scopes'];
                } else {
                    return $this->formatResponse(false, __('access_token_is_not_valid'), 'client.whatsapp.settings', []);
                }
            }
            $scopes = $responseData['data']['scopes'];
            $dataAccessExpiresAt = isset($responseData['data']['data_access_expires_at']) ?
                (new \DateTime())->setTimestamp($responseData['data']['data_access_expires_at']) : null;
            $dataExpiresAt = isset($responseData['data']['expires_at']) ?
                (new \DateTime())->setTimestamp($responseData['data']['expires_at']) : null;
            curl_close($ch);
            $clientSetting       = $this->model
                ->where('type', TypeEnum::WHATSAPP->value)
                ->where('client_id', Auth::user()->client->id)
                ->first();
                
            if ($clientSetting) {   
                $clientSetting                      = $this->model->where('type', TypeEnum::WHATSAPP)->where('client_id', Auth::user()->client->id)->first();
                $clientSetting->access_token        = $accessToken;
                $clientSetting->phone_number_id     = $request->phone_number_id;
                $clientSetting->business_account_id = $request->business_account_id;
                $clientSetting->app_id              = $responseData['data']['app_id'] ?? $request->app_id;
                $clientSetting->is_connected        = $is_connected;
                $clientSetting->token_verified      = $token_verified;
                $clientSetting->scopes              = $scopes;
                $clientSetting->granular_scopes     = $responseData['data']['granular_scopes'] ?? null;
                $clientSetting->name    = $responseData['data']['application'] ?? null;
                $clientSetting->data_access_expires_at = $dataAccessExpiresAt;
                $clientSetting->expires_at          = $dataExpiresAt;
                $clientSetting->fb_user_id          = $responseData['data']['user_id'] ?? null;
                $clientSetting->update();
            } else {

                $clientSetting = $this->model->create([
                    'type'                => TypeEnum::WHATSAPP,
                    'client_id'           => Auth::user()->client->id,
                    'access_token'        => $accessToken,
                    'phone_number_id'     => $request->phone_number_id,
                    'business_account_id' => $request->business_account_id,
                    'app_id'              => $responseData['data']['app_id'] ?? $request->app_id,
                    'is_connected'        => $is_connected,
                    'token_verified'      => $token_verified,
                    'scopes'              => $scopes,
                    'granular_scopes'     => $responseData['data']['granular_scopes'] ?? null,
                    'name'    => $responseData['data']['application'] ?? null,
                    'data_access_expires_at' => $dataAccessExpiresAt,
                    'expires_at'          => $dataExpiresAt,
                    'fb_user_id'          => $responseData['data']['user_id'] ?? null,
                ]);
            }
            $this->loadTemplate($clientSetting);
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'client.whatsapp.settings', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('Whatsapp Setting Update', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.whatsapp.settings', []);
        }
    }

    public function whatsAppsync($request)
    {
        DB::beginTransaction();
        try {
            $clientSetting = @Auth::user()->client->whatsappSetting;
            if (!$clientSetting) {
                return $this->formatResponse(false, __('whatsapp_setting_not_found'), 'client.whatsapp.settings', []);
            }
            $accessToken = $clientSetting->access_token;
            $url = 'https://graph.facebook.com/debug_token?input_token=' . $accessToken . '&access_token=' . $accessToken;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $responseData = json_decode($response, true);
            if (isset($responseData['error'])) {
                return $this->formatResponse(false, $responseData['error']['message'], 'client.whatsapp.settings', []);
            }
            if (isset($responseData['data']['is_valid']) && $responseData['data']['is_valid'] === true) {
                $clientSetting->is_connected = 1;
                $clientSetting->token_verified = 1;
                $clientSetting->scopes = $responseData['data']['scopes'];
                $clientSetting->granular_scopes = $responseData['data']['granular_scopes'] ?? null;
                // $clientSetting->application_name = $responseData['data']['application'] ?? null;
                $clientSetting->data_access_expires_at = isset($responseData['data']['data_access_expires_at']) ?
                    (new \DateTime())->setTimestamp($responseData['data']['data_access_expires_at']) : null;
                $clientSetting->expires_at = isset($responseData['data']['expires_at']) ?
                    (new \DateTime())->setTimestamp($responseData['data']['expires_at']) : null;
                $clientSetting->fb_user_id = $responseData['data']['user_id'] ?? null;
                $clientSetting->name    = $responseData['data']['application'] ?? null;
                $clientSetting->save();
                $this->loadTemplate($clientSetting);
                DB::commit();
                return $this->formatResponse(true, __('whatsapp_settings_synced_successfully'), 'client.whatsapp.settings', []);
            } else {
                return $this->formatResponse(false, __('access_token_is_not_valid'), 'client.whatsapp.settings', []);
            }
        } catch (\Throwable $e) {
            DB::rollback();
            \Log::error('WhatsApp Sync Error: ', [$e->getMessage()]);
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            return $this->formatResponse(false, $e->getMessage(), 'client.whatsapp.settings', []);
        }
    }


    public function removeWhatsAppToken($request, $id)
    {
        if (isDemoMode()) {
            return $this->formatResponse(false, __('this_function_is_disabled_in_demo_server'), 'client.whatsapp.settings', []);
        }
        DB::beginTransaction();
        try {
            $clientSetting = $this->model->where('type', TypeEnum::WHATSAPP)
                ->where('client_id', Auth::user()->client->id)
                ->where('id', $id)
                ->firstOrFail();
            $clientSetting->delete();
            $this->template->where('client_id', Auth::user()->client->id)->where('client_setting_id',$clientSetting->id)->delete();
            $this->client->where('id', Auth::user()->client->id)->update([
                'webhook_verify_token' => Str::random(40)
            ]);

            DB::commit();
            return $this->formatResponse(true, __('deleted_successfully'), 'client.whatsapp.settings', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('WhatsApp Setting Remove', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.whatsapp.settings', []);
        }
    }

    public function telegramUpdate($request)
    {
        DB::beginTransaction();
        try {
            $result           = [];
            $is_connected     = 0;
            $webhook_verified = 0;
            $token_verified   = 0;
            $accessToken      = $request->access_token;
            config(['telegram.bots.mybot.token' => $accessToken]);
            $result           = Telegram::getMe();
            if (!isset($result) || $result['is_bot'] !== true) {
                return $this->formatResponse(false, __('bot_token_is_not_valid'), 'client.telegram.settings', []);
            }
            $is_connected = 1;
            $token_verified = 1;
            $scopes = [];
            if ($result['can_join_groups']) {
                $scopes[] = 'can_join_groups';
            }
            if (!empty($result['can_read_all_group_messages'])) {
                $scopes[] = 'can_read_all_group_messages';
            }
            if (!empty($result['supports_inline_queries'])) {
                $scopes[] = 'supports_inline_queries';
            }
            $webhookResponse = $this->setWebhook($accessToken);
            $webhook_verified = !empty($webhookResponse) && $webhookResponse === true ? 1 : 0;

            $clientSetting    = $this->model
                ->where('type', TypeEnum::TELEGRAM->value)
                ->where('client_id', Auth::user()->client->id)
                ->first();
            if ($clientSetting) {
                $clientSetting                              = $this->model->where('type', TypeEnum::TELEGRAM)->where('client_id', Auth::user()->client->id)->first();
                $clientSetting->access_token                = $accessToken;
                $clientSetting->is_connected                = $is_connected;
                $clientSetting->webhook_verified            = $webhook_verified;
                $clientSetting->bot_id                      = $result['id'];
                $clientSetting->name                        = $result['first_name'];
                $clientSetting->username                    = $result['username'];
                $clientSetting->token_verified              = $token_verified;
                $clientSetting->scopes                      = $scopes ?? [];
                $clientSetting->granular_scopes             = $scopes ?? [];
                $clientSetting->update();
            } else {
                $clientSetting = $this->model->create([
                    'type'                        => TypeEnum::TELEGRAM,
                    'client_id'                   => Auth::user()->client->id,
                    'bot_id'                      => $result['id'],
                    'name'                        => $result['first_name'],
                    'username'                    => $result['username'],
                    'access_token'                => $accessToken,
                    'phone_number_id'             => $request->phone_number_id,
                    'business_account_id'         => $request->business_account_id,
                    'is_connected'                => $is_connected,
                    'webhook_verified'            => $webhook_verified,
                    'token_verified'              => $token_verified,
                    'scopes'                      => $scopes,
                    'granular_scopes'             => $scopes,
                ]);
            }
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'client.telegram.settings', []);
        } catch (\Throwable $e) {

            DB::rollback();

            if (config('app.debug')) {
                dd($e->getMessage());
            }

            \Log::info('Telegram Setting Update', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.telegram.settings', []);
        }
    }

    public function removeTelegramToken($request, $id)
    {
        if (isDemoMode()) {
            return $this->formatResponse(false, __('this_function_is_disabled_in_demo_server'), 'client.telegram.settings', []);
        }
        DB::beginTransaction();
        try {
            $clientSetting = $this->model->where('type', TypeEnum::TELEGRAM)
                ->where('client_id', Auth::user()->client->id)
                ->where('id', $id)
                ->firstOrFail();
            $botgroups     = $this->botGroup->where('client_setting_id', $clientSetting->id)->get();
            foreach ($botgroups as $botgroup) {
                $this->groupSubscriber->where('group_id', $botgroup->id)->delete();
                $this->contact->where('group_id', $botgroup->id)->delete();
                $messages = $this->message->whereHas('contact', function ($query) use ($botgroup) {
                    $query->where('group_id', $botgroup->id);
                })->get();
                foreach ($messages as $message) {
                    $message->delete();
                }
                $botgroup->delete();
            }
            $clientSetting->delete();
            DB::commit();

            return $this->formatResponse(true, __('deleted_successfully'), 'client.telegram.settings', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('Telegram Setting Remove', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.telegram.settings', []);
        }
    }

    public function telegramSettingsync($id)
    {
        DB::beginTransaction();
        try {
            $result           = [];
            $is_connected     = 0;
            $webhook_verified = 0;
            $token_verified   = 0;
            $accessToken      = @Auth::user()->client->telegramSetting->access_token;
            config(['telegram.bots.mybot.token' => $accessToken]);

            $result           = Telegram::getMe();

            if (!isset($result) || $result['is_bot'] !== true) {
                return $this->formatResponse(false, __('bot_token_is_not_valid'), 'client.telegram.settings', []);
            }

            $is_connected = 1;
            $token_verified = 1;
            $scopes = [];
            if ($result['can_join_groups']) {
                $scopes[] = 'can_join_groups';
            }
            if (!empty($result['can_read_all_group_messages'])) {
                $scopes[] = 'can_read_all_group_messages';
            }
            if (!empty($result['supports_inline_queries'])) {
                $scopes[] = 'supports_inline_queries';
            }
            $webhookResponse = $this->setWebhook($accessToken);
            $webhook_verified = !empty($webhookResponse) && $webhookResponse === true ? 1 : 0;
            $clientSetting    = $this->model
                ->where('type', TypeEnum::TELEGRAM->value)
                ->where('client_id', Auth::user()->client->id)
                ->findOrFail($id);
            $clientSetting->access_token                = $accessToken;
            $clientSetting->is_connected                = $is_connected;
            $clientSetting->webhook_verified            = $webhook_verified;
            $clientSetting->bot_id                      = $result['id'];
            $clientSetting->name                        = $result['first_name'];
            $clientSetting->username                    = $result['username'];
            $clientSetting->token_verified              = $token_verified;
            $clientSetting->scopes                      = $scopes ?? [];
            $clientSetting->granular_scopes             = $scopes ?? [];
            $clientSetting->update();
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'client.telegram.settings', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('Telegram Setting Update', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.telegram.settings', []);
        }
    }


    public function billingDetailsUpdate($request, $id)
    {
        $client                   = Client::findOrFail($id);
        $client->billing_name     = $request->billing_name;
        $client->billing_email    = $request->billing_email;
        $client->billing_address  = $request->billing_address;
        $client->billing_city     = $request->billing_city;
        $client->billing_state    = $request->billing_state;
        $client->billing_zip_code = $request->billing_zipcode;
        $client->billing_country  = $request->billing_country;
        $client->billing_phone    = $request->billing_phone;
        $client->save();
    }

    public function aiCredentialUpdate($request)
    {
        DB::beginTransaction();
        try {
            $this->client->where('id', Auth::user()->client->id)->update(
                [
                    'open_ai_key' => $request->ai_secret_key,
                ]
            );
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'ai_writer.setting', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('AI Credential Update', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'ai_writer.setting', []);
        }
    }
}
