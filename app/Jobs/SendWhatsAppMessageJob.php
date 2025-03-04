<?php

namespace App\Jobs;

use Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use App\Models\Client; // Replace with your client model
use App\Models\Contact; // Replace with your contact model
use App\Models\Message; // Replace with your message model
use App\Enums\MessageStatusEnum; // Replace with your message status enum
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessageJob
{
    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        $client = Client::find($this->message->client_id);
        $contact = Contact::find($this->message->contact_id);

        try {
            $accessToken = getClientWhatsAppAccessToken($client);
            $whatsapp_phone_number_id = getClientWhatsAppPhoneID($client);
            
            $whatsappCloudApi = new WhatsAppCloudApi([
                'from_phone_number_id' => $whatsapp_phone_number_id,
                'access_token' => $accessToken,
            ]);

            switch ($this->message->message_type) {
                case 'text':
                    $response = $whatsappCloudApi->sendTextMessage($contact->phone, $this->message->value);
                    break;
                case 'image':
                    $response = $whatsappCloudApi->sendImage($contact->phone, new LinkID($this->message->header_image));
                    break;
                case 'audio':
                    $response = $whatsappCloudApi->sendAudio($contact->phone, new LinkID($this->message->header_audio));
                    break;
                case 'video':
                    $response = $whatsappCloudApi->sendVideo($contact->phone, new LinkID($this->message->header_video), '');
                    break;
                case 'document':
                    $document_name = basename($this->message->header_document);
                    $document_link = $this->message->header_document;
                    $response = $whatsappCloudApi->sendDocument($contact->phone, new LinkID($document_link), $document_name, '');
                    break;
                default:
                    throw new \Exception('Unsupported message type.');
            }

            $message_body = json_decode($response->body(), true);
            
            if (!empty($message_body['messages'])) {
                $this->message->message_id = $message_body['messages'][0]['id'];
                $this->message->status = MessageStatusEnum::SENT;
            } else {
                $this->message->error = isset($message_body['error']) ? $message_body['error']['message'] : 'Unknown';
                $this->message->status = MessageStatusEnum::FAILED;
            }
            $this->message->update();

            // Update conversation or other necessary actions
            // $this->updateConversation($client, $contact);

        } catch (\Exception $e) {
           \Log::error($e->getMessage());
            $this->message->error = $e->getMessage();
            $this->message->status = MessageStatusEnum::FAILED;
            $this->message->update();
        }
    }
}
