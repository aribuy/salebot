<?php

namespace App\Repositories\Webhook;

use App\Models\Flow;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use App\Models\Segment;
use App\Enums\StatusEnum;
use App\Traits\CommonTrait;
use App\Models\ContactsList;
use App\Models\ClientSetting;
use App\Traits\BotReplyTrait;
use App\Traits\WhatsAppTrait;
use App\Models\OneSignalToken;
use App\Enums\MessageStatusEnum;
use App\Services\WhatsAppService;
use App\Models\ContactRelationList;
use Illuminate\Support\Facades\Log;
use App\Models\ContactRelationSegments;

class WhatsappRepository
{
    use WhatsAppTrait, BotReplyTrait,CommonTrait;
    private $clientModel;
    private $country;
    private $contact;
    private $message;
    private $flow;
    protected $whatsappService;

    public function __construct(
        Client $clientModel,
        Country $country,
        Contact $contact,
        Message $message,
        Flow $flow,
        WhatsAppService $whatsappService
    ) {
        $this->clientModel = $clientModel;
        $this->contact = $contact;
        $this->message = $message;
        $this->whatsappService = $whatsappService;
        $this->country = $country;
        $this->flow = $flow;
    }

    public function verifyToken($request, $token)
    {
        $hubMode = $request->hub_mode;
        Log::info('$hubMode', [$hubMode]);

        $hubVerifyToken = $request->hub_verify_token;
        Log::info('$hubVerifyToken', [$hubVerifyToken]);

        $hubChallenge = $request->hub_challenge;
        Log::info('$hubChallenge', [$hubChallenge]);

        $client = $this->clientModel->where('webhook_verify_token', $hubVerifyToken)->with('whatsappSetting')->first();
        Log::info('$client', [$client]);

        if (!empty($client) && !empty($client->webhook_verify_token)) {
            if ($hubMode && $hubMode === 'subscribe') {
                if (!empty($client->whatsappSetting)) {
                    $whatsappSetting = $client->whatsappSetting;
                } else {
                    $whatsappSetting = new ClientSetting();
                    $whatsappSetting->client_id = $client->id;
                    $whatsappSetting->save();
                    $client->load('whatsappSetting');
                }
                $whatsappSetting->webhook_verified = 1;
                $whatsappSetting->save();
                Log::info('$whatsappSetting', [$whatsappSetting]);

                return response($hubChallenge, 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json([], 403);
            }
        } else {
            return response()->json([], 403);
        }
    }



    public function receiveResponse($request, $token)
    {
        // Log::info('receiveResponse', [$request]);
        $client = $this->clientModel->where('webhook_verify_token', $token)->with('whatsappSetting')->first();
        if (!empty($client) && !empty($client->webhook_verify_token)) {
            try {
                $value = $request->entry[0]['changes'][0]['value'];
                // Log the messages if they exist
                // if (isset($value['messages'])) {
                //     Log::info('receive Response messages', [$value['messages']]);
                // }
                if (isset($value['statuses'])) {
                    $this->handleStatusUpdate($value, $client);
                } elseif (isset($value['messages'])) {
                    $this->handleIncomingMessage($value, $client);
                }
                return response()->json(['send' => true]);
            } catch (\Throwable $e) {
                Log::info('Throwable', [$e->getMessage()]);
                return response()->json(['send' => false, 'error' => __('an_unexpected_error_occurred_please_try_again_later.'), 'data' => $request]);
            }
        } else {
            return response()->json(['send' => false]);
        }
    }



    private function handleStatusUpdate($value, $client)
{
    try {
        $campaign = null;
        // Safely access 'statuses' array
        if (isset($value['statuses'][0])) {
            $statusInfo = $value['statuses'][0];
            $message_id = $statusInfo['id'] ?? null;
            $conversation = $statusInfo['conversation'] ?? null;
            // Additional check for conversation data
            if (isset($conversation)) {
                // Process conversation data if necessary
            }
            // Safely access 'message' data
            $message = $this->message->where('message_id', $message_id)->first();
            if ($message) {
                $incomming_status = $statusInfo['status'] ?? null;
                $message->status = $incomming_status;
                $message->error = $statusInfo['errors'][0]['message'] ?? '';
                $message->update();

                if (!empty($message->campaign)) {
                    $campaign = $message->campaign;

                    if ($incomming_status === 'failed' && isset($statusInfo['errors'][0]['code'])) {
                        $error_code = $statusInfo['errors'][0]['code'];
                        if ($this->isErrorStoppingCampaign($error_code)) {
                            $campaign->status = StatusEnum::STOPPED;
                            $campaign->errors = $this->getErrorMessage($error_code);
                            $campaign->update();
                        }
                    }
                    
                    // Update campaign metrics
                    switch ($incomming_status) {
                        case 'delivered':
                            if ($message->status !== 'read') {
                                $campaign->total_delivered += 1;
                            }
                            break;
                        case 'sent':
                            if ($message->status !== 'delivered') {
                                $campaign->total_sent += 1;
                            }
                            break;
                        case 'read':
                            $campaign->total_read += 1;
                            break;
                        case 'failed':
                            $campaign->total_failed += 1;
                            break;
                    }
                    $campaign->save();
                }
            }
        } else {
            Log::info('handleStatusUpdate', ['No status info found']);
        }
    } catch (\Exception $e) {
        Log::info('handleStatusUpdate Exception', [$e->getMessage()]);
        return false;
    }
}


    private function getErrorMessage($error_code)
    {
        $whatsapp_error = config('static_array.whatsapp_error');
        $index = array_search($error_code, array_column($whatsapp_error, 'code'));
        $description = $index !== false ? $whatsapp_error[$index]['description'] : 'Unknown Error';
        return $description;
    }

    private function isErrorStoppingCampaign($error_code)
    {
        $stop_campaign_errors = config('static_array.stop_campaign_errors');
        return in_array($error_code, $stop_campaign_errors);
    }

    private function handleIncomingMessage($value, $client)
    {
        try {
            // Check if required keys exist in the array
            if (!isset($value['messages']) || !isset($value['contacts'])) {
                Log::info('Required keys are missing in the incoming message array.');
                // throw new \Exception('Required keys are missing in the incoming message array.');
            }
            Log::info('handle Incoming Message ', [$value['messages']]);
            $phone = $value['messages'][0]['from'];
            $type = $value['messages'][0]['type'];
            $name = $value['contacts'][0]['profile']['name'] ?? 'Unknown';
            $contact_id = $value['contacts'][0]['wa_id'];
            $message_id = $value['messages'][0]['id'];
            $contact = $this->contact
            ->where('client_id', $client->id)
            ->where(function ($query) use ($phone) {
                $query->where('phone', $phone)
                      ->orWhere('phone', "+" . $phone);
            })
            ->first();
        
            if (!$contact) {
                $contact = new Contact();
                $contact->name = $name;
                $contact->phone = $phone;
                $contact->contact_id = $contact_id;
                $contact->client_id = $client->id;
                $contact->country_id = $this->whatsappService->extractCountryCode($phone);
                $contact->has_conversation = 1;
                $contact->is_verified = 1;
                $contact->has_unread_conversation = 1;
                $contact->last_conversation_at = now();
                $contact->status = 1;
                $contact->save();

                $contactList = ContactsList::where('client_id', $client->id)->where('name', 'Uncategorized')->first();
                if (empty($contactList)) {
                    $contactList = new ContactsList();
                    $contactList->name = 'Uncategorized';
                    $contactList->client_id = $client->id;
                    $contactList->save();
                }

                ContactRelationList::firstOrCreate([
                    'contact_id' => $contact->id,
                    'contact_list_id' => $contactList->id,
                ]);

                $defaultSegment = Segment::firstOrCreate([
                    'client_id' => $client->id,
                    'title' => 'Default',
                ], [
                    'client_id' => $client->id,
                    'title' => 'Default',
                ]);

                ContactRelationSegments::firstOrCreate([
                    'contact_id' => $contact->id,
                    'segment_id' => $defaultSegment->id,
                ]);

            } else {

                $contact->update([
                    'contact_id' => $contact_id,
                    'is_verified' => 1,
                    'has_conversation' => 1,
                    'has_unread_conversation' => 1,
                    'last_conversation_at' => now(),
                ]);
            }
            $content = $value;
            $is_contact_msg = true;
            $is_campaign_msg = false;
            $this->saveIncommingMessage($contact, $content, $client, $is_contact_msg, $is_campaign_msg, $type, $message_id);
        } catch (\Exception $e) {
            Log::info('handleIncomingMessage ', [$e->getMessage()]);
            return false;
        }
    }

    private function saveIncommingMessage($contact, $content, $client, $is_contact_msg, $is_campaign_msg, $type, $message_id)
    {
        try {
            $existingMessage = Message::where('message_id', $message_id)->first();
            if ($existingMessage) {
                Log::info('Message with the same message_id already exists', ['message_id' => $message_id]);
                return false;
            }
            $message = new Message();
            $message->contact_id = $contact->id;
            $message->message_id = $message_id;
            $message->client_id = $client->id;
            $notified_message = '';
            if ($type == 'image') {
                $response = $this->handleReceivedMedia($client, $content['messages'][0]['image']['id'], '.jpg');
                $message->header_image = $response;
                $notified_message = __('sent_an_image');
            } elseif ($type == 'audio') {
                $response = $this->handleReceivedMedia($client, $content['messages'][0]['audio']['id'], '.mp3');
                $message->header_audio = $response; 
                $notified_message = __('sent_an_audio_file');
            } elseif ($type == 'video') {
                $response = $this->handleReceivedMedia($client, $content['messages'][0]['video']['id'], '.mp4');
                $message->header_video = $response;
                $notified_message = __('sent_a_video');
            } elseif ($type == 'text') {
                $response = $content['messages'][0]['text']['body'];
                $message->value = $response;
                $notified_message = $response;
            } elseif ($type == 'contacts' || $type == 'contact') {
                $message->contacts = json_encode($content['messages'][0]['contacts']);
                $notified_message = __('shared_a_contact_with_you');
            } elseif ($type == 'document') {
                $response = $this->handleReceivedMedia($client, $content['messages'][0]['document']['id'], '.pdf');
                $message->header_document = $response;
                $notified_message = __('shared_a_document_with_you');
            } elseif ($type == 'location') {
                $response = 'https://www.google.com/maps?q=' . $content['messages'][0]['location']['latitude'] . ',' . $content['messages'][0]['location']['longitude'];
                $message->header_location = $response;
                $notified_message = __('shared_a_location_with_you');
            } elseif ($type == 'button') {

                $buttonsData = $content['messages'][0]['button'];
                $formattedButtons = [];
                if (isset($buttonsData)) {
                    $formattedButtons[] = [
                        'type' => $content['messages'][0]['type'],
                        'payload' => $buttonsData['payload'] ?? '',
                        'text' => $buttonsData['text'] ?? ''
                    ];
                }
                $message->buttons = json_encode($formattedButtons);
                $notified_message = $content['messages'][0]['button']['text'];

            } elseif ($type == 'interactive') {

                $buttonsData = $content['messages'][0]['interactive'];
                $formattedButtons = [];
                if (isset($buttonsData['button_reply'])) {
                    $formattedButtons[] = [
                        'type' => $buttonsData['type'],
                        'id' => $buttonsData['button_reply']['id'] ?? '',
                        'text' => $buttonsData['button_reply']['title'] ?? ''
                    ];
                }
                $message->buttons = json_encode($formattedButtons);
                $notified_message = $buttonsData['button_reply']['title'];

            }else if($type == 'unsupported'){
                $response = __('message_type_is_currently_not_supported');
                $message->value = $response;
                $notified_message = $response;
                $message->error = $response;
            }
            if (isset($content['messages'][0]['context'])) {
                $message->context_id = $content['messages'][0]['context']['id'];
            }
            $message->message_type = $type;
            $message->components = null;
            $message->campaign_id = null;
            $message->is_contact_msg = $is_contact_msg;
            $message->is_campaign_msg = $is_campaign_msg;
            $message->status = MessageStatusEnum::DELIVERED;
            $message->save();
                // Update status if needed
            $message->status = MessageStatusEnum::DELIVERED;
            $message->update();
                if (setting('is_pusher_notification_active')) {
                event(new \App\Events\ReceiveUpcomingMessage($client));
            }
    
            if (setting('is_onesignal_active')) {
                $this->pushNotification([
                    'ids' => OneSignalToken::where('client_id', $client->id)->pluck('subscription_id')->toArray(),
                    'message' => $notified_message,
                    'heading' => $contact->name,
                    'url' => route('client.chat.index', ['contact' => $contact->id]),
                ]);
            }
    
            $contact->update([
                'last_conversation_at' => now(),
                'has_conversation' => 1,
                'has_unread_conversation' => 1
            ]);
    
            if ($message && $contact->bot_reply) {
                $this->QuickReply($message);
            }
    
            return true;
        } catch (\Exception $e) {
            Log::error('Save Incoming Message Exception', [$e->getMessage()]);
            return false;
        }
    }
    
}
