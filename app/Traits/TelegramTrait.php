<?php
namespace App\Traits;
use Telegram\Bot\Api;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\Message;
use App\Models\BotGroup;
use App\Enums\StatusEnum;
use App\Enums\MessageEnum;
use App\Traits\CommonTrait;
use App\Models\ClientSetting;
use App\Models\OneSignalToken;
use App\Models\GroupSubscriber;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

trait TelegramTrait
{
    use CommonTrait;
    public $TELEGRAM_BASE_URL = 'https://api.telegram.org/';

    private function getClientSettingByToken($token)
    {
        return ClientSetting::where('access_token', $token)
            ->where('type', TypeEnum::TELEGRAM)
            ->active()
            ->with('client')
            ->first();
    }

    public function handleFile($fileId, $clientSetting)
    {
        $storage = setting('default_storage') ?: 'local';
        try {
            $getFileResponse = Telegram::getFile(['file_id' => $fileId]);
            if (isset($getFileResponse['file_path'])) {
                $filePath = $getFileResponse['file_path'];
                $fileUrl = $this->TELEGRAM_BASE_URL . 'file/bot' . config('telegram.bots.mybot.token') . '/' . $filePath;
                $responseImage = Http::withHeaders([])->get($fileUrl);
                if ($responseImage->successful()) {
                    $fileContents = $responseImage->getBody()->getContents();
                    if ($fileContents !== false) {
                        $fileExtension = '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                        $fileName    = date('YmdHis') . '_original_' . rand(1, 500) . '.' . $fileExtension;
                        // Store file
                        if ($storage == 'wasabi') {
                            $filePath = "images/media/$fileName";
                            $path     = Storage::disk('wasabi')->put($filePath, $fileContents, 'public');
                            return Storage::disk('wasabi')->url($filePath);
                        } elseif ($storage == 's3') {
                            $filePath = "images/media/$fileName";
                            $path     = Storage::disk('s3')->put($filePath, $fileContents, 'public');
                            return Storage::disk('s3')->url($filePath);
                        } else {
                            $localDirectory = public_path("images/media/");
                            if (!file_exists($localDirectory)) {
                                mkdir($localDirectory, 0755, true);
                            }
                            $localPath      = "{$localDirectory}{$fileName}";
                            file_put_contents($localPath, $fileContents);
                            return url("public/images/media/$fileName");
                        }
                    } else {
                        Log::error('Failed to fetch file content');
                    }
                } else {
                    Log::error('Failed to fetch file from Telegram');
                }
            } else {
                Log::error('Telegram getFile method request failed');
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred: ' . $e->getMessage());
        }
        return null;
    }

    private function handleNewGroupInfo($updates, $clientSetting)
    {
        try {
            
            $telegramBotName = $clientSetting->username;
            $telegramBotId = $clientSetting->id;
            $addGroupInfo =
                // isset($updates['message']['new_chat_member']['is_bot']) 
                // &&
                // isset($updates['message']['new_chat_member']['username']) 
                // &&
                isset($updates['message']['chat']['type']) &&
                ($updates['message']['chat']['type'] == 'group' || $updates['message']['chat']['type'] == 'supergroup')
                // &&
                // $updates['message']['new_chat_member']['username'] == $telegramBotName 
                // && $updates['message']['new_chat_member']['is_bot'] == 'false'
            ;
            if ($addGroupInfo) {
                $chatId = $updates['message']['chat']['id'] ?? '';
                $groupName = $updates['message']['chat']['title'] ?? '';
                $supergroupSubscriberId = $chatId . '-' . $telegramBotId;
                if (!empty($chatId) && !BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)->where('client_id', $clientSetting->client_id)->exists()) {
                    
                    
                    $botGroup = new BotGroup();
                    $botGroup->name = $groupName;
                    $botGroup->group_id = $chatId;
                    $botGroup->client_setting_id = $clientSetting->id;
                    $botGroup->client_id = $clientSetting->client_id;
                    $botGroup->type = "telegram";
                    $botGroup->group_type = $updates['message']['chat']['type'] ?? '';
                    $botGroup->supergroup_subscriber_id  = $supergroupSubscriberId;
                    $botGroup->status = 1;
                    $botGroup->save();

                    $existingContact               = Contact::where('group_chat_id', $chatId)->where('client_id', $clientSetting->client_id)->first();
                    if (!$existingContact) {
                        $newContact                = new Contact();
                        $newContact->contact_id    = $chatId;
                        $newContact->name          = $groupName;
                        $newContact->username      = $groupName;
                        $newContact->group_chat_id = $chatId;
                        $newContact->group_id      = $botGroup->id;
                        $newContact->client_id     = $clientSetting->client_id;
                        $newContact->type          = TypeEnum::TELEGRAM;
                        $newContact->save();

                    }else{
                        Contact::where('group_chat_id', $chatId)
                        ->update([
                            'name' => $groupName,
                            'username' => $groupName,
                            'is_blacklist' => '0',
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('handleNewGroupInfo', [$e->getMessage()]);
        }
    }

    private function processGroupSubscribers($updates, $clientSetting)
    {
        try {
            $chatId = $updates['message']['chat']['id'] ?? null;
            $userId = $updates['message']['from']['id'] ?? '';
            $telegramGroupId = $this->getTelegramGroupId($updates, $clientSetting);
            // Log::error('$subscriber1', [$updates['message']['from']]);
            if ($telegramGroupId) {
                $isGroupMessage = isset($updates['message']['chat']['type']) &&
                    ($updates['message']['chat']['type'] == 'group' || $updates['message']['chat']['type'] == 'supergroup');
                $isBot = isset($updates['message']['new_chat_member'])
                    && $updates['message']['new_chat_member']['is_bot'] == true ? true : false;
                if ($isGroupMessage) {
                    if (isset($updates['message']['new_chat_member'])) {
                        $this->addNewGroupSubscriber($updates, $telegramGroupId, $clientSetting);
                    } else {
                        $this->updateExistingGroupSubscriber($updates, $telegramGroupId, $clientSetting);
                    }
                }
            }
            if (isset($updates['message']['from'])) {
                $subscriberId = $updates['message']['from']['id'];
                $firstName = $updates['message']['from']['first_name'] ?? '';
                $lastName = $updates['message']['from']['last_name'] ?? '';
                $userName = $updates['message']['from']['username'] ?? '';
                $groupSubscriberId = $subscriberId . '-' . $telegramGroupId;
                // if(isset($updates['message']['from']) && $updates['message']['from']['is_bot'] == false){
                // if(isset($updates['message']['from'])){
                if (!empty($subscriberId) && !GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->where('client_id', $clientSetting->client_id)->exists()) {
                    $scopes = [];
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $subscriberId,
                    ]);
                    $responseData = json_decode($response, true);
                    if ($responseData !== null && isset($responseData['photos'])) {
                        $photos = $responseData['photos'];
                        if (!empty($photos)) {
                            $firstPhoto = $photos[0][0];
                            $fileId = $firstPhoto['file_id'];
                            $avatar =  $this->handleFile($fileId, $clientSetting);
                        }
                    }
                    $subscriber = new GroupSubscriber();
                    Log::info('4 addNewGroupSubscriber $subscriber', [$subscriberId]);

                    $subscriber->unique_id = $subscriberId;
                    $subscriber->name = $firstName . ' ' . $lastName;
                    $subscriber->username = $userName;
                    $subscriber->avatar = $avatar;
                    // $subscriber->phone = null;
                    $subscriber->client_id = $clientSetting->client_id;
                    $subscriber->group_chat_id = null;
                    $subscriber->group_subscriber_id  = $groupSubscriberId;
                    $subscriber->group_id = $telegramGroupId;
                    // $subscriber->is_admin = $this->isGroupAdmin($updates, $clientSetting);
                    $subscriber->scopes = $scopes;
                    $subscriber->is_bot =  0;
                    $subscriber->is_left_group = 0;
                    $subscriber->type = 'telegram';
                    $subscriber->is_blacklist = 0;
                    $subscriber->status = 1;
                    $subscriber->save();
                }
                // }
            }
        } catch (\Exception $e) {
            Log::error('processGroupSubscribers', [$e->getMessage()]);
        }
    }

    private function getTelegramGroupId($updates, $clientSetting)
    {
        try {
            $chatId = $updates['message']['chat']['id'] ?? null;
            if (empty($chatId)) {
                $chatId = $updates['chat_member']['chat']['id'] ?? null;
            }
            $supergroupSubscriberId = $chatId . '-' . $clientSetting->id;
            $telegramGroup = BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)
                ->where('client_id', $clientSetting->client_id)
                ->first();
            return $telegramGroup->id ?? null;
        } catch (\Exception $e) {
            Log::error('getTelegramGroupId', [$e->getMessage()]);
        }
    }

    private function addNewGroupSubscriber($updates, $telegramGroupId, $clientSetting)
    {
        try {
            $subscriberId = $updates['message']['new_chat_member']['id'] ?? $updates['message']['from']['id'];
            Log::info('addNewGroupSubscriber $subscriberId', [$updates]);

            $firstName = $updates['message']['new_chat_member']['first_name'] ?? '';
            $lastName = $updates['message']['new_chat_member']['last_name'] ?? '';
            $userName = $updates['message']['new_chat_member']['username'] ?? '';
            $groupSubscriberId = $subscriberId . '-' . $telegramGroupId;

            if (isset($updates['message']['new_chat_member']['is_bot']) && !$updates['message']['new_chat_member']['is_bot']) {
                if (!empty($subscriberId) && !GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->where('client_id', $clientSetting->client_id)->exists()) {
                    $scopes = [];
                    // $scopes = $this->extractScopes($updates['my_chat_member']['new_chat_member']);
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $subscriberId,
                    ]);
                    $responseData = json_decode($response, true);
                    if ($responseData !== null && isset($responseData['photos'])) {
                        $photos = $responseData['photos'];
                        if (!empty($photos)) {
                            $firstPhoto = $photos[0][0];
                            $fileId = $firstPhoto['file_id'];
                            $avatar =  $this->handleFile($fileId, $clientSetting);
                        }
                    }
                    $subscriber = new GroupSubscriber();
                    Log::info('1 addNewGroupSubscriber $subscriber', [$subscriberId]);

                    $subscriber->unique_id = $subscriberId;
                    $subscriber->name = $firstName . ' ' . $lastName;
                    $subscriber->username = $userName;
                    $subscriber->avatar = $avatar;
                    // $subscriber->phone = null;
                    $subscriber->client_id = $clientSetting->client_id;
                    $subscriber->group_chat_id = $updates['message']['chat']['id'] ?? '';
                    $subscriber->group_subscriber_id  = $groupSubscriberId;
                    $subscriber->group_id = $this->getTelegramGroupId($updates, $clientSetting);
                    $subscriber->is_admin = $this->isGroupAdmin($updates, $clientSetting);
                    $subscriber->scopes = $scopes;
                    $subscriber->is_bot = $updates['message']['new_chat_member']['is_bot'] ?? 1;
                    $subscriber->is_left_group = 0;
                    $subscriber->type = 'telegram';
                    $subscriber->is_blacklist = 0;
                    $subscriber->status = 1;
                    $subscriber->save();
                } else {
                    GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)
                        ->update([
                            'is_left_group' => '0',
                            'is_blacklist' => '0',
                            //  'removed' => '0'
                        ]);
                }
            }
            if (isset($updates['message']['new_chat_member']) && $updates['message']['new_chat_member']['is_bot'] == true) {
                if (!empty($subscriberId) && !GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->where('client_id', $clientSetting->client_id)->exists()) {
                    $scopes = [];
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $subscriberId,
                    ]);
                    $responseData = json_decode($response, true);
                    if ($responseData !== null && isset($responseData['photos'])) {
                        $photos = $responseData['photos'];
                        if (!empty($photos)) {
                            $firstPhoto = $photos[0][0];
                            $fileId = $firstPhoto['file_id'];
                            $avatar =  $this->handleFile($fileId, $clientSetting);
                        }
                    }
                    $subscriber = new GroupSubscriber();
                    Log::info('2 addNewGroupSubscriber $subscriber', [$subscriberId]);

                    $subscriber->unique_id = $subscriberId;
                    $subscriber->name = $firstName . ' ' . $lastName;
                    $subscriber->username = $userName;
                    $subscriber->avatar = $avatar;
                    // $subscriber->phone = null;
                    $subscriber->client_id = $clientSetting->client_id;
                    $subscriber->group_chat_id = $updates['message']['chat']['id'] ?? '';
                    $subscriber->group_subscriber_id  = $groupSubscriberId;
                    $subscriber->group_id = $this->getTelegramGroupId($updates, $clientSetting);
                    $subscriber->is_admin = $this->isGroupAdmin($updates, $clientSetting);
                    $subscriber->scopes = $scopes;
                    $subscriber->is_bot = $updates['message']['new_chat_member']['is_bot'] ? 1 : 0;
                    $subscriber->is_left_group = 0;
                    $subscriber->type = 'telegram';
                    $subscriber->is_blacklist = 0;
                    $subscriber->status = 1;
                    $subscriber->save();
                }
            }


            if (isset($updates['my_chat_member']['new_chat_member']) && $updates['my_chat_member']['new_chat_member']['status'] == "administrator") {
                Log::error('is_bot', [$updates['message']['new_chat_member']['user']['is_bot']]);
                if (!empty($subscriberId) && !GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->where('client_id', $clientSetting->client_id)->exists()) {
                    $scopes = [];
                    // $scopes = $this->extractScopes($updates['my_chat_member']['new_chat_member']);
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $subscriberId,
                    ]);
                    $responseData = json_decode($response, true);
                    if ($responseData !== null && isset($responseData['photos'])) {
                        $photos = $responseData['photos'];
                        if (!empty($photos)) {
                            $firstPhoto = $photos[0][0];
                            $fileId = $firstPhoto['file_id'];
                            $avatar =  $this->handleFile($fileId, $clientSetting);
                        }
                    }
                    $subscriber = new GroupSubscriber();
                    // Log::info('3 addNewGroupSubscriber $subscriber', [$subscriberId]);
                    $subscriber->unique_id = $subscriberId;
                    $subscriber->name = $firstName . ' ' . $lastName;
                    $subscriber->username = $userName;
                    $subscriber->avatar = $avatar;
                    // $subscriber->phone = null;
                    $subscriber->client_id = $clientSetting->client_id;
                    $subscriber->group_chat_id = $updates['message']['chat']['id'] ?? '';
                    $subscriber->group_subscriber_id  = $groupSubscriberId;
                    $subscriber->group_id = $this->getTelegramGroupId($updates, $clientSetting);
                    $subscriber->is_admin = $this->isGroupAdmin($updates, $clientSetting);
                    $subscriber->scopes = $scopes;
                    $subscriber->is_bot = 1;
                    $subscriber->is_left_group = 0;
                    $subscriber->type = 'telegram';
                    $subscriber->is_blacklist = 0;
                    $subscriber->status = 1;
                    $subscriber->save();
                }
            }
        } catch (\Exception $e) {
            Log::error('addNewGroupSubscriber', [$e->getMessage()]);
        }
    }

    private function updateExistingGroupSubscriber($updates, $telegramGroupId, $clientSetting)
    {
        // Log::info('updateExistingGroupSubscriber', [$updates]);
        try {
            $subscriberId = $updates['message']['from']['id'] ?? '';
            $firstName = $updates['message']['from']['first_name'] ?? '';
            $lastName = $updates['message']['from']['last_name'] ?? '';
            $userName = $updates['message']['from']['username'] ?? '';
            $chatId = $updates['message']['chat']['id'] ?? '';
            $groupSubscriberId = $subscriberId . '-' . $telegramGroupId;
            if (!empty($subscriberId) && !GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->where('client_id', $clientSetting->client_id)->exists() && $userName != $clientSetting->username) {
                    $avatar = null;
                    $telegram = new Api($clientSetting->access_token);
                    $response = $telegram->getUserProfilePhotos([
                        'user_id' => $subscriberId,
                    ]);
                    $responseData = json_decode($response, true);
                    if ($responseData !== null && isset($responseData['photos'])) {
                        $photos = $responseData['photos'];
                        if (!empty($photos)) {
                            $firstPhoto = $photos[0][0];
                            $fileId = $firstPhoto['file_id'];
                            $avatar =  $this->handleFile($fileId, $clientSetting);
                        }
                    }
                // Log::info('4 updateExistingGroupSubscriber $subscriber', [$subscriberId]);
                $subscriber = new GroupSubscriber();
                $subscriber->unique_id =  $subscriberId;
                $subscriber->name =  $firstName . ' ' . $lastName;
                $subscriber->username =  $userName;
                $subscriber->avatar =  $avatar;
                $subscriber->client_id =  $clientSetting->client_id;
                $subscriber->group_chat_id =  $chatId;
                $subscriber->group_subscriber_id =  $groupSubscriberId;
                $subscriber->group_id =  $this->getTelegramGroupId($updates, $clientSetting);
                $subscriber->is_bot =  0;
                $subscriber->is_blacklist =  0;
                $subscriber->save();

            }
        } catch (\Exception $e) {
            Log::error('updateExistingGroupSubscriber', [$e->getMessage()]);
        }
    }

    private function removeBotOrSubscriber($updates, $clientSetting)
    {
        // Log::info('removeBotOrSubscriber', [$updates]);
        try {
            $telegramGroupId = $this->getTelegramGroupId($updates, $clientSetting);
            if ($telegramGroupId) {
                $removeBotOrSubscriber = isset($updates['message']['left_chat_member']) &&
                    isset($updates['message']['chat']['type']) &&
                    ($updates['message']['chat']['type'] == 'group' || $updates['message']['chat']['type'] == 'supergroup');

                if ($removeBotOrSubscriber) {
                    $this->deleteLeftChatMember($updates, $telegramGroupId, $clientSetting);
                }
            }
        } catch (\Exception $e) {
            Log::error('removeBotOrSubscriber', [$e->getMessage()]);
        }
    }

    private function deleteLeftChatMember($updates, $telegramGroupId, $clientSetting)
    {
        // Log::info('deleteLeftChatMember', [$updates]);
        try {
            $chatId = $updates['message']['chat']['id'] ?? '';
            $messageId = $updates['message']['message_id'] ?? '';
            // $this->deleteMessage($clientSetting, $chatId, $messageId);
            $getUserName = $updates['message']['left_chat_member']['username'] ?? '';
            $removeBot = $getUserName == $clientSetting->username;
            if ($removeBot) {
                // BotGroup::where('id', $telegramGroupId)->where('client_id', $clientSetting->client_id)->delete();
            } else {
                $subscriberId = $updates['message']['left_chat_member']['id'] ?? '';
                $groupSubscriberId = $subscriberId . '-' . $telegramGroupId;

                if ($subscriberId == $updates['message']['from']['id']) {
                    GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)
                        ->update(['is_left_group' => '1']);
                } else {
                    GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)
                        ->update(['is_left_group' => '1']);
                }
            }
        } catch (\Exception $e) {
            Log::error('deleteLeftChatMember', [$e->getMessage()]);
        }
    }

    private function deleteMessage($clientSetting, $chatId, $messageId)
    {
        // Log::info('deleteMessage', [$messageId]);
        try {
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);
        } catch (\Exception $e) {
            Log::error('deleteMessage', [$e->getMessage()]);
        }
    }

    private function isGroupAdmin($updates, $clientSetting)
    {
        if (
            isset($updates['message']['new_chat_member']['user']['is_bot']) &&
            isset($updates['message']['chat']['type']) &&
            isset($updates['message']['new_chat_member']['user']['username'])
        ) {
            $is_bot = $updates['message']['new_chat_member']['user']['is_bot'];
            $chat_type = $updates['message']['chat']['type'];
            $username = $updates['message']['new_chat_member']['user']['username'];
            if (($chat_type == 'group' || $chat_type == 'supergroup') &&
                $username == $clientSetting->username && $is_bot
            ) {
                return true;
            }
        }
        return false;
    }

    private function updateBotAdminStatus($updates, $clientSettings) {
        try {
        $isBotAdmin = 
            isset($updates['my_chat_member']['new_chat_member']['user']['is_bot']) &&
            isset($updates['my_chat_member']['chat']['type']) &&
            isset($updates['my_chat_member']['new_chat_member']['user']['username']) &&
            ($updates['my_chat_member']['chat']['type'] == 'group' || $updates['my_chat_member']['chat']['type'] == 'supergroup') &&
            $updates['my_chat_member']['new_chat_member']['user']['username'] == $clientSettings->username;
    
        if ($isBotAdmin) {
            $groupId = $updates['my_chat_member']['chat']['id'] ?? '';
            $supergroupSubscriberId = $groupId . '-' . $clientSettings->id;
            $chatMemberStatus = $updates['my_chat_member']['new_chat_member']['status'] ?? '';
            $isAdmin = !empty($groupId) && $chatMemberStatus == 'administrator';
    
            DB::table('telegram_groups')
                ->where(['supergroup_subscriber_id' => $supergroupSubscriberId])
                ->update(['is_admin' => $isAdmin ? '1' : '0']);
        }
        return true;
        } catch (\Exception $e) {
            \Log::error('Process Telegram Message ' . $e->getMessage());
        }
    }
    

        private function processMessage($updates, $clientSetting)
        {
            try {
                if (empty($updates['message'])) {
                    \Log::error('No message found in updates.');
                    // throw new \Exception("No message found in updates.");
                    return ;
                }
                $message_response = $updates['message'];
                $from = $message_response['from'];
                $subscriberId = $from['id'];
                $chatId = $message_response['chat']['id'];
                $groupName = $message_response['chat']['title'];
                if (isset($message_response['text']) || isset($message_response['photo']) || isset($message_response['audio']) || isset($message_response['video']) || isset($message_response['document']) || isset($message_response['location'])) {
                    $supergroupSubscriberId = $chatId . '-' . $clientSetting->id;
                    $existingGroup = BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)->where('client_id', $clientSetting->client_id)->first();
                    $existingContact = Contact::where('group_chat_id', $chatId)->where('client_id', $clientSetting->client_id)->first();
                    if (!$existingContact) {
                        $newContact = new Contact();
                        $newContact->contact_id = $chatId;
                        $newContact->name = $groupName;
                        $newContact->username = $groupName;
                        $newContact->group_chat_id = $chatId;
                        $newContact->group_id = $existingGroup ?? $existingGroup->id;
                        $newContact->client_id = $clientSetting->client_id;
                        $newContact->type = TypeEnum::TELEGRAM;
                        $newContact->save();
                    }
                    $groupSubscriberId = $subscriberId . '-' . $existingGroup->id;
                    $existingSubscriber = GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->where('client_id', $clientSetting->client_id)->first();
                    $contact = $existingContact ?: $newContact;
                    $message = new Message();
                    $message->message_id = $message_response['message_id'];
                    $message->contact_id = $contact->id;
                    $message->group_subscriber_id = $existingSubscriber ?? $existingSubscriber->id;
                    $message->client_id = $clientSetting->client_id;
                    $message->source = TypeEnum::TELEGRAM;
                    $notified_message = '';
                    if (isset($message_response['text'])) {
                        $message->value = $message_response['text'];
                        $message->message_type = MessageEnum::TEXT;
                        $notified_message = $message_response['text'];
                    }
                    if (isset($message_response['photo']) && isset($message_response['photo'][0]['file_id'])) {
                        $message->header_image = $this->handleFile($message_response['photo'][1]['file_id'], $clientSetting);
                        $message->message_type = MessageEnum::IMAGE;
                        $notified_message = __('sent_an_image');
                    }
                    if (isset($message_response['audio']) && isset($message_response['audio']['file_id'])) {
                        $message->header_audio = $this->handleFile($message_response['audio']['file_id'], $clientSetting);
                        $message->message_type = MessageEnum::AUDIO;
                        $notified_message = __('sent_an_audio_file');
                    }
                    if (isset($message_response['video']) && isset($message_response['video']['file_id'])) {
                        $message->header_video = $this->handleFile($message_response['video']['file_id'], $clientSetting);
                        $message->message_type = MessageEnum::VIDEO;
                        $notified_message = __('sent_a_video');
                    }
                    if (isset($message_response['document']) && isset($message_response['document']['file_id'])) {
                        $message->header_document = $this->handleFile($message_response['document']['file_id'], $clientSetting);
                        $message->message_type = MessageEnum::DOCUMENT;
                        $notified_message = __('shared_a_document_with_you');
                    }
                    if (isset($message_response['location'])) {
                        $latitude = $message_response['location']['latitude'];
                        $longitude = $message_response['location']['longitude'];
                        $locationUrl = 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude;
                        $message->header_location = $locationUrl;
                        $notified_message = __('shared_a_location_with_you');
                    }
                    $message->status = MessageStatusEnum::DELIVERED;
                    $message->is_contact_msg = 1;
                    $message->save();

                    if (setting('is_pusher_notification_active')) {
                        event(new \App\Events\ReceiveUpcomingMessage($clientSetting->client));
                    }
                    if (setting('is_onesignal_active')) {

                        $this->pushNotification([
                            'ids' => OneSignalToken::where('client_id', $clientSetting->client->id)->pluck('subscription_id')->toArray(),
                            'message' => $notified_message,
                            'heading' => $contact->name,
                            'url' => route('client.chat.index', ['contact' => $contact->id]),
                        ]);

                    }

                    $contact->update(['last_conversation_at' => now(), 'has_conversation' => 1, 'has_unread_conversation' => 1]);
                }
            } catch (\Exception $e) {
                \Log::error('Process Telegram Message ' . $e->getMessage());
            }
        }
        

   
    private function extractScopes($newChatMember)
    {
        $scopes = [];

        $permissions = [
            'can_be_edited',
            'can_manage_chat',
            'can_change_info',
            'can_delete_messages',
            'can_invite_users',
            'can_restrict_members',
            'can_pin_messages',
            'can_manage_topics',
            'can_promote_members',
            'can_manage_video_chats',
            'can_post_stories',
            'can_edit_stories',
            'can_delete_stories',
            'is_anonymous',
            'can_manage_voice_chats'
        ];

        foreach ($permissions as $permission) {
            if (!empty($newChatMember[$permission])) {
                $scopes[] = $permission;
            }
        }

        return $scopes;
    }



    private function processLeftChatMember($updates, $clientSetting)
    {
        if (isset($updates['message']['left_chat_member'])) {
            $userId                 = $updates['message']['left_chat_member']['id'];
            $chatId                 = $updates['message']['chat']['id'];
            $supergroupSubscriberId = $userId . '-' . $clientSetting->id;
            $existingGroup          = BotGroup::where('supergroup_subscriber_id', $supergroupSubscriberId)->where('client_id', $clientSetting->client_id)->first();
            if ($existingGroup) {
                $groupSubscriberId  = $userId . '-' . $existingGroup->id;
                GroupSubscriber::where('group_subscriber_id', $groupSubscriberId)->update(['is_left_group' => 1, 'status' => 0]);
            }
        }
    }

    private function setWebhook($accessToken)
    {
        try {
            $webhookUrl = route('telegram.webhook', $accessToken);
            if (strpos($webhookUrl, 'http://') === 0) {
                $webhookUrl = 'https://' . substr($webhookUrl, 7);
            }
            $webhookResponse = Telegram::setWebhook(['url' => $webhookUrl]);
            return $webhookResponse;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return [];
        }
    }

    public function removeWebhook()
    {
        $response = Telegram::removeWebhook();
        return $response;
    }

    public function sendTelegramMessage($message, $type)
    {
        try {
            if (!empty($message->client) && !empty($message->client->telegramSetting->access_token)) {
                config(['telegram.bots.mybot.token' => $message->client->telegramSetting->access_token]);
            } else {
                Log::error('Telegram API Error', ['Client or Telegram settings not found.']);
                // throw new \Exception('Client or Telegram settings not found.');
            }
            $chatId = @$message->contact->group_chat_id;
            $botToken = @$message->client->telegramSetting->access_token;
            if (empty($chatId) || empty($botToken)) {
                Log::error('Telegram API Error', ['Chat ID or Bot Token is missing.']);
                // throw new \Exception('Chat ID or Bot Token is missing.');
            }
            // \Log::info('Sending Telegram message', [
            //     'chat_id' => $chatId,
            //     'bot_token' => $botToken,
            //     'message_type' => $message->message_type,
            //     'message_value' => $message->value ?? null,
            //     'media' => $message->header_image ?? $message->header_audio ?? $message->header_video ?? $message->header_document ?? null,
            // ]);

            $response = null;

            if ($message->message_type == MessageEnum::TEXT || $message->message_type == MessageEnum::TEXT->value) {
                $response = $this->sendMessage($botToken, $chatId, $message->value);
            } elseif ($message->message_type == MessageEnum::IMAGE || $message->message_type == MessageEnum::IMAGE->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_image, 'photo');
            } elseif ($message->message_type == MessageEnum::AUDIO || $message->message_type == MessageEnum::AUDIO->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_audio, 'audio');
            } elseif ($message->message_type == MessageEnum::VIDEO || $message->message_type == MessageEnum::VIDEO->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_video, 'video');
            } elseif ($message->message_type == MessageEnum::DOCUMENT || $message->message_type == MessageEnum::DOCUMENT->value) {
                $response = $this->sendMedia($botToken, $chatId, $message->header_document, 'document');
            } else {
                Log::error('Unsupported message type');
                // throw new \Exception('Unsupported message type.');
            }
            if ($response && $response->successful()) {
                $messageId = $response->json('result.message_id');
                $message->message_id = $messageId;
                $message->status = MessageStatusEnum::READ;
            } else {
                if ($response && $response->json('ok') === false) {
                    \Log::error('Telegram API Error', ['error' => $response->json()]);
                    $message->status = MessageStatusEnum::FAILED->value;
                }
            }

            $message->update();
            $campaign = $message->campaign;
            if ($campaign) {
                if ($message->status == MessageStatusEnum::READ) {
                    $campaign->total_sent += 1;
                    $campaign->total_delivered += 1;
                    $campaign->total_read += 1;
                } elseif ($message->status == MessageStatusEnum::FAILED) {
                    $campaign->total_failed += 1;
                }
                $campaign->save();
            }


            $this->conversationUpdate($message->client_id, $message->contact_id);
        } catch (\Exception $e) {
            if ($message->campaign) {
                $campaign = $message->campaign;
                DB::table('campaigns')->where('id', $campaign->id)->update([
                    'status' => StatusEnum::PROCESSED
                ]);
            }
            $errorMessage = isset(json_decode($e->getMessage(), true)['error']['message']) ? json_decode($e->getMessage(), true)['error']['message'] : 'Unknown';
            $message->error = $errorMessage;
            $message->status = MessageStatusEnum::FAILED;
            $message->save();
            \Log::error('Error sending Telegram message', ['error' => $e->getMessage()]);
            if (config('app.debug')) {
                dd($e->getMessage());
            }
        }
    }

    private function sendMessage($botToken, $chatId, $text)
    {
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
        return Http::post($apiUrl, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
   
    private function sendMedia($botToken, $chatId, $filePath, $mediaType)
    {

        $fileName = basename($filePath);
        $apiUrl = "https://api.telegram.org/bot{$botToken}/send{$mediaType}";
        return Http::attach(
            $mediaType,
            file_get_contents($filePath),
            $fileName
        )->post($apiUrl, [
            'chat_id' => $chatId,
        ]);
        
    }

    public function sendAudio($botToken, $chatId, $userID)
    {
        $url = $this->TELEGRAM_BASE_URL . "/bot{$botToken}/getChatMember";
        $response = Http::post($url, [
            'chat_id' => $chatId,
            'user_id' => $userID
        ]);
        if ($response->successful()) {
            return $response->json();
        } else {
            return null;
        }
    }

    public function syncSubscriber($botToken, $chatId, $userID)
    {
        $url = "https://api.telegram.org/bot{$botToken}/getChatMember";
        $response = Http::post($url, [
            'chat_id' => $chatId,
            'user_id' => $userID,
        ]);
        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->json(); // or throw an exception, log error, etc.
        }
    }

    public function getGroupInfo($botToken, $chatId)
    {
        $telegram = new Api($botToken);
        try {
            $chatInfo = $telegram->getChat(['chat_id' => $chatId]);
            if ($chatInfo->isOk()) {
                $chatId = $chatInfo->getId();
                $groupName = $chatInfo->getTitle();
                $groupType = $chatInfo->getType();
                return [
                    'id' => $chatId,
                    'name' => $groupName,
                    'type' => $groupType
                ];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            Log::info('getGroupInfo ', [$e->getMessage()]);

            return null;
        }
    }

    public function getSubscriberPhoto($botToken, $user_id)
    {
        $clientSetting = null;
        try {
            $telegram = new Api($botToken);
            $response = $telegram->getUserProfilePhotos([
                'user_id' => $user_id,
            ]);
            $responseData = json_decode($response, true);
            if ($responseData !== null && isset($responseData['photos'])) {
                $photos = $responseData['photos'];
                if (!empty($photos)) {
                    $firstPhoto = $photos[0][0];
                    $fileId = $firstPhoto['file_id'];
                    $avatar =  $this->handleFile($fileId, $clientSetting);
                    \Log::info('$avatar', [$avatar]);
                    return $avatar;
                }
            }
            return null;
        } catch (\Exception $e) {
            Log::info('getSubscriberPhoto ', [$e->getMessage()]);
            return null;
        }
    }

    public function muteChatMember($botToken,$chatId, $telegramGroupSubscriberId = '', $untilDate = '')
    {
        $apiUrl = 'https://api.telegram.org/bot' . $botToken . '/';
        $muteEndpoint = 'restrictChatMember';
        $muteData = [
            'chat_id' => $chatId,
            'user_id' => $telegramGroupSubscriberId,
            'permissions' => json_encode([
                'can_send_messages' => false,
                'can_send_media_messages' => false,
                'can_send_polls' => false,
                'can_send_other_messages' => false,
                'can_add_web_page_previews' => false,
                'can_change_info' => false,
                'can_invite_users' => false,
                'can_pin_messages' => false
            ]),
            'until_date' => $untilDate
        ];
        $muteUrl = $apiUrl . $muteEndpoint;
        $ch = curl_init($muteUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($muteData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            \Log::error('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return ['ok' => false, 'error' => curl_error($ch)];
        }
        curl_close($ch);
        $responseData = json_decode($response, true);
        if (!$responseData['ok']) {
            \Log::error('Error muting chat member: ' . json_encode($responseData));
        }
        return $responseData;
    }

    public function banUnbanChatMember($botToken,$method,$chatId,$subscriberId)
    {
        $url = 'https://api.telegram.org/bot' . $botToken . '/' . $method;
        $params = [
            'chat_id' => $chatId,
            'user_id' => $subscriberId
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            \Log::error('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return ['ok' => false, 'error' => curl_error($ch)];
        }
        curl_close($ch);
        $responseData = json_decode($response, true);
        if (!$responseData['ok']) {
            \Log::error('Error in ' . $method . ': ' . json_encode($responseData));
        }
        return $responseData;
    }

    public function pinMessage($botToken, $chatId, $messageId)
    {
        $url = "https://api.telegram.org/bot{$botToken}/pinChatMessage";
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'disable_notification' => true // Optional: Set to true to send the message silently
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            \Log::error('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return ['ok' => false, 'error' => curl_error($ch)];
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if (!$responseData['ok']) {
            \Log::error('Error in pinChatMessage: ' . json_encode($responseData));
        }

        return $responseData;
    }


}
