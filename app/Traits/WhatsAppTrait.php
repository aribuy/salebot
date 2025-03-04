<?php
namespace App\Traits;
use App\Models\Client;
use App\Enums\StatusEnum;
use App\Traits\CommonTrait;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;

trait WhatsAppTrait
{
    use SendNotification, CommonTrait;

    public $facebook_api = 'https://graph.facebook.com/v19.0/';

    private function sendWhatsAppCampaignMessage($message)
    {
        $client = Client::find($message->client_id);
        try {
            $accessToken             = getClientWhatsAppAccessToken($client);
            $whatspp_phone_number_id = getClientWhatsAppPhoneID($client);
            $template                = $message->campaign->template ?? $message->template;
            if (!empty($template)) {
                $contact                 = $message->contact;
                if ($message->contact->status == 1) {
                    $whatsapp_cloud_api = new WhatsAppCloudApi([
                        'from_phone_number_id' => $whatspp_phone_number_id,
                        'access_token'         => $accessToken,
                    ]);
                    $component_header   = json_decode($message->component_header)  ?? [];
                    $component_body     = json_decode($message->component_body)    ?? [];
                    $component_buttons  = json_decode($message->component_buttons) ?? [];
                    $components         = new Component($component_header, $component_body, $component_buttons);
                    $message_api        = $whatsapp_cloud_api->sendTemplate($contact->phone, $template->name, $template->language, $components);
                    $message_body       = json_decode($message_api->body(), true);
                    if (!empty($message_body['messages'])) {
                        $message->message_id = $message_body['messages'][0]['id'];
                        $message->status     = MessageStatusEnum::SENT;
                        $message->update();
                    } else {
                        $message->error  = isset($message_body['error']) ? $message_body['error']['message'] : 'Unknown';
                        $message->status = MessageStatusEnum::FAILED;
                        $message->update();
                    }
                }
                if ($message->campaign) {
                    $campaign = $message->campaign;
                    $campaignMessages = $campaign->messages();
                    if ($campaignMessages->count() == 1) {
                        DB::table('campaigns')->where('id', $campaign->id)->update([
                            'status' => StatusEnum::PROCESSED
                        ]);
                    }
                }
                return true;
            } else {
                $message->error  = 'Template is empty';
                $message->status = MessageStatusEnum::FAILED;
                $message->update();
                return false;
            }
        } catch (\Exception $e) {
            if ($message->campaign) {
                $campaign = $message->campaign;
                DB::table('campaigns')->where('id', $campaign->id)->update([
                    'status' => StatusEnum::PROCESSED
                ]);
            }
            Log::error($e->getMessage());
            $errorMessage = isset(json_decode($e->getMessage(), true)['error']['message']) ? json_decode($e->getMessage(), true)['error']['message'] : 'Unknown';
            $message->error = $errorMessage;
            $message->status = MessageStatusEnum::FAILED;
            $message->save();
            return false;
        }
    }

    public function sendWhatsAppMessage($message, $message_type)
    {
        $client = Client::active()->find($message->client_id);
        try { 
            $response = [];
            $accessToken = getClientWhatsAppAccessToken($client);
            $whatsapp_phone_number_id = getClientWhatsAppPhoneID($client);
            // Log::error('$whatsapp_phone_number_id',[$whatsapp_phone_number_id]);
            $contact = $message->contact;
            $whatsapp_cloud_api = new WhatsAppCloudApi([ 
                'from_phone_number_id' => $whatsapp_phone_number_id,
                'access_token' => $accessToken,
            ]);

              // Check if context_id is not empty and set reply context
            if (!empty($message->context_id)) {
                $whatsapp_cloud_api->replyTo($message->context_id);
            }

            if ($message_type == 'text') {
                $response = $whatsapp_cloud_api->sendTextMessage($contact->phone, $message->value);
            } elseif ($message_type == 'image') {
                $link_id = new LinkID($message->header_image);
                $response = $whatsapp_cloud_api->sendImage($contact->phone, $link_id);
            } elseif ($message_type == 'audio') {
                Log::error('Audio URL:', ['url' => $message->header_audio]);
                $link_id = new LinkID($message->header_audio);
                Log::error('LinkID for Audio:', ['link_id' => $link_id]);
                $response = $whatsapp_cloud_api->sendAudio($contact->phone, $link_id);
                Log::error('Audio Send Response:', ['response' => $response]);
                
            } elseif ($message_type == 'video') {
                $caption = $message->caption ?? '';
                $link_id = new LinkID($message->header_video);
                $response = $whatsapp_cloud_api->sendVideo($contact->phone, $link_id, $caption);
                
            } elseif ($message_type == 'document') {  
                $document_name = basename($message->header_document);
                $caption = $message->caption ?? '';
                $document_link = $message->header_document;
                $link_id = new LinkID($document_link);
                $response = $whatsapp_cloud_api->sendDocument($contact->phone, $link_id, $document_name, $caption);
            } 

            elseif ($message_type == 'location') {
                $header_location = $message->header_location;
                $response = $whatsapp_cloud_api->sendTextMessage($contact->phone, $header_location);
            } 
            elseif ($message_type == 'interactive_button') {
                $messageResponse = json_decode($message->buttons, true); // Decode as associative array
                $buttons = [];
                foreach ($messageResponse as $key => $button) {
                    $title = $button['text'];
                    if (strlen($title) >= 1 && strlen($title) <= 20) {
                        $buttons[] = new Button($button['id'], $title);
                    } else {
                        // Log::error('Button title length invalid', ['id' => $button['id'], 'text' => $title]);
                        if (strlen($title) > 20) {
                            $title = substr($title, 0, 20);
                            $buttons[] = new Button($button['id'], $title);
                        }
                    }
                }
                $action = new ButtonAction($buttons);
                $header = $message->header_text ?? '';
                if (strlen($header) > 60) {
                    $header = substr($header, 0, 57) . '...';
                }
                $footer = $message->footer_text ?? '';
                if (strlen($footer) > 60) {
                    $footer = substr($footer, 0, 57) . '...';
                }
                $response = $whatsapp_cloud_api->sendButton(
                    $contact->phone,
                    $message->value,
                    $action,
                    $header,
                    $footer
                ); 
            }
            $message_body = json_decode($response->body(), true);
            if (!empty($message_body['messages'])) {
                $message->message_id = $message_body['messages'][0]['id'];
                $message->status = MessageStatusEnum::SENT;
            } else {
                $message->error = isset($message_body['error']) ? $message_body['error']['message'] : 'Unknown';
                $message->status = MessageStatusEnum::FAILED;
                // $this->conversationUpdate($message->client_id, $message->contact_id);
                // return true;
            }
            $message->update();
            $this->conversationUpdate($message->client_id, $message->contact_id);
            return true;
        } catch (\Exception $e) {
            Log::error('sendWhatsAppMessage Exception', [$e->getMessage()]);
            $errorMessage = isset(json_decode($e->getMessage(), true)['error']['message']) ? json_decode($e->getMessage(), true)['error']['message'] : strip_tags($e->getMessage());
            $message->error = $errorMessage;
            $message->status = MessageStatusEnum::FAILED;
            $message->save();
            return false;
        }
    }



    public function handleReceivedMedia($client, $media_id, $fileExtension = '.jpg')
    {
        $storage = setting('default_storage') != '' || setting('default_storage') != null ? setting('default_storage') : 'local';
        $url = $this->facebook_api . $media_id;
        $accessToken = getClientWhatsAppAccessToken($client);
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->get($url);
            $content = json_decode($response->body(), true);
            // Check if the response content is valid
            if (!$content || !isset($content['url'])) {
                Log::error('Invalid response content', ['content' => $content]);
                // throw new \Exception('Invalid response content');
            }
            $responseImage = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->withoutVerifying()->get($content['url']);
            $fileContents = $responseImage->getBody()->getContents();
            if ($fileContents === false) {
                Log::error('Error downloading and storing media');
                // throw new \Exception('Error downloading image');
            }
            if ($storage == 'wasabi') {
                $fileName = "images/media/{$content['id']}{$fileExtension}";
                $path = Storage::disk('wasabi')->put($fileName, $fileContents, 'public');
                return Storage::disk('wasabi')->url($fileName);
            } elseif ($storage == 's3') {
                $fileName = "images/media/{$content['id']}{$fileExtension}";
                $path = Storage::disk('s3')->put($fileName, $fileContents, 'public');
                return Storage::disk('s3')->url($fileName);
            } else {
                $directory = public_path('images/media');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true); // Create the directory if it doesn't exist
                }
                $fileName = "{$content['id']}{$fileExtension}";
                $filePath = "{$directory}/{$fileName}";
                file_put_contents($filePath, $fileContents);
                return asset("public/images/media/{$fileName}");     
            }
        } catch (\Exception $e) {
            Log::error('Error downloading and storing media: ' . $e->getMessage());
            return null;
        }
    }
}
