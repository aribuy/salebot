<?php

namespace App\Traits;

use App\Models\Flow;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Message;
use App\Models\BotReply;
use App\Models\FlowNode;
use App\Enums\MessageEnum;
use App\Enums\BotReplyType;
use App\Traits\CommonTrait;
use Illuminate\Support\Str;
use App\Traits\WhatsAppTrait;
use Orhanerday\OpenAi\OpenAi;
use App\Enums\MessageStatusEnum;
use App\Models\ContactFlowState;
use Illuminate\Support\Facades\DB;
use App\Models\ContactRelationList;
use Illuminate\Support\Facades\Log;
use App\Models\ContactRelationSegments;

trait BotReplyTrait
{
    use CommonTrait, WhatsAppTrait; 

    public $facebook_api = 'https://graph.facebook.com/v19.0/';

    protected function getContactFlowState($contact)
    {
        return ContactFlowState::where('contact_id', $contact->id)->first();;
    }

    protected function getFlowIdByMessage($conversation_text, $client, $contact_id)
    {
        $segments = ContactRelationSegments::where('contact_id', $contact_id)->pluck('segment_id')->toArray();
        $lists = ContactRelationList::where('contact_id', $contact_id)->pluck('contact_list_id')->toArray();
        $node = FlowNode::where('client_id', $client->id)
            ->where('type', 'starter-box')
            ->whereHas('flow', function ($query) use ($segments, $lists) {
                $query->where('status', 1);
                $query->where(function ($subQuery) use ($segments, $lists) {
                    if (!empty($lists)) {
                        $subQuery->whereIn('contact_list_id', $lists);
                    }
                      // if (!empty($segments)) {
                    //     $subQuery->whereIn('segment_id', $segments);
                    // }
                });
            })
            ->with('flow')
            ->get()
            ->sortByDesc(function ($node) {
                return $node->flow->created_at ?? $node->flow->updated_at;
            })
            ->filter(function ($node) use ($conversation_text) {
                $data = $node->data;

                if (isset($data['keyword'], $data['matching_types'])) {
                    if ($data['matching_types'] === 'exacts') {
                        return trim(Str::lower($data['keyword'])) === trim(Str::lower($conversation_text));
                    } else {
                        return str_contains(Str::lower($conversation_text), Str::lower($data['keyword']));
                    }
                }

                return false;
            })
            ->first();
        return $node ? $node->flow_id : null;
    }

    protected function getStarterNodeId($flowId)
    {
        $starterNode = FlowNode::where('flow_id', $flowId)
            ->whereHas('flow', function ($query) {
                $query->where('status', 1);
            })
            ->where('type', 'starter-box')
            ->first();
        return $starterNode ? $starterNode->node_id : null;
    }

    protected function getCurrentNode($flowId)
    {
        $starterNode = FlowNode::where('flow_id', $flowId)
            ->whereHas('flow', function ($query) {
                $query->where('status', 1);
            })
            ->where('type', 'starter-box')
            ->first();
        return $starterNode ? $starterNode->node_id : null;
    }

    protected function hasFlowReply($flowId)
    {
        $starterNode = FlowNode::where('flow_id', $flowId)
            ->whereHas('flow', function ($query) {
                $query->where('status', 1);
            })
            ->where('type', 'starter-box')
            ->first();
        return $starterNode ? $starterNode->node_id : null;
    }



    public function QuickReply($message)
    {
        DB::beginTransaction();
        try {
            $flow = null;
            $contact = Contact::active()->find($message->contact_id);
            if (!$contact) {
                Log::error('Contact', ["Contact not found"]);
                // throw new \Exception("Contact not found");
                return null;
            }
            $client = Client::active()->find($message->client_id);
            if (!$client) {
                Log::error('$client', ["Client not found"]);
                // throw new \Exception("Client not found");
                return null;
            }
            $conversation_text = trim(Str::lower($message->value));
            $currentFlowState = ContactFlowState::where('contact_id', $contact->id)->first();
            if(!empty($conversation_text)){
                $flow = $this->getFlowIdByMessage($conversation_text, $client, $contact->id);
            }
            if ($currentFlowState) {
                $flow_id = $currentFlowState->flow_id;
                $this->flowReply($flow_id, $contact, $client, $message);
            } elseif (!empty($flow)) {
                $this->flowReply($flow, $contact, $client, $message);
            } else {
                $this->BOTReply($message, $contact, $client);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Log::error('send Bot Reply Message', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return false;
        }
    }


    private function determineNextNodeReply($flow, $current_node, $message)
    {
        try {
            $data = [];
            $current_node_type = $current_node->type;
            $has_auto_reply = false;
            $button = null;
            $next_node_id = null;
            $next_node_type = null;
            $sourceHandle = null;
            $delay_time = 2;


            $edge = $flow->edges->where('source', $current_node->node_id);

            if (($current_node_type == 'box-with-button' && $message->message_type == 'interactive') || $current_node_type == 'box-with-list' && $message->message_type == 'interactive') {

                $buttons = json_decode($message->buttons);
                $sourceHandle = $buttons[0]->id . 'right';
                $edge = $edge->where('sourceHandle', $sourceHandle)->first();
            } elseif ($current_node_type == 'box-with-button' && $message->message_type !== 'interactive') {
                return [
                    'has_next_node' => true,
                    'has_auto_reply' => $has_auto_reply,
                    'node_id' => null,
                    'type' => null,
                    'data' => [],
                ];
            } else {
                $edge = $edge->first();
            }

            if ($edge) {
                $node = $flow->nodes->firstWhere('node_id', $edge->target);
            }

            $delay_time = isset($node['duration']) ? $node['duration'] : 0;


            if (!isset($node)) {
                return [
                    'has_next_node' => false,
                    'has_auto_reply' => $has_auto_reply,
                    'node_id' => null,
                    'type' => null,
                    'data' => [],
                ];
            }

            // Determine the next node type and data
            $node_type = $node->type;
            switch ($node_type) {
                case 'box-with-title':
                    $data['reply_text'] = $node->data['text'];
                    $has_auto_reply = true;
                    break;

                case 'node-image':
                    $data['image'] = $node->data['image'];
                    $has_auto_reply = true;
                    break;

                case 'box-with-audio':
                    $data['audio'] = $node->data['audio'];
                    $has_auto_reply = true;
                    break;

                case 'box-with-video':
                    $data['video'] = $node->data['video'];
                    $has_auto_reply = true;
                    break;

                case 'box-with-file':
                    $data['file'] = $node->data['file'];
                    $has_auto_reply = true;
                    break;

                case 'box-with-template':
                    $data['template'] = $node->data['template'];
                    $has_auto_reply = false;
                    break;

                case 'box-with-condition':
                    $data['condition'] = $node->data['condition'];
                    $has_auto_reply = false;
                    break;

                case 'box-with-list':
                    $data['list'] = $node->data;
                    $has_auto_reply = false;
                    break;

                case 'box-with-button':
                    $data['button'] = $node->data;
                    $has_auto_reply = false;
                    break;

                case 'box-with-location':
                    $data['location'] = 'https://www.google.com/maps?q=' . $node->data['lat'] . ',' . $node->data['long'];
                    $has_auto_reply = true;
                    break;

                default:
                    $data['reply_text'] = $node->data['text'];
                    $has_auto_reply = true;
                    break;
            }

            $data['reply_type'] = $node_type;
            $data['buttons'] = $button;
            $data['current_node_id'] = $node->node_id;
            $data['next_node_id'] = $next_node_id;
            $data['next_node_type'] = $next_node_type;

            return [
                'has_auto_reply' => $has_auto_reply,
                'has_next_node' => true,
                'delay_time' => $delay_time,
                'node_id' => $node->node_id,
                'type' => $node->type,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('Determine Next Node Error', ['error' => $e->getMessage()]);
            return false;
        }
    }


    public function BOTReply($message, $contact, $client)
    {
        if (empty($message)) {
            return null;
        }
        $reply_message = null;
        $message_text = '';
        try {
            $bot_replies = BotReply::where('client_id', $message->client_id)->where('status', 1)->get();
            foreach ($bot_replies as $reply) {
                $keywords = explode(',', $reply->keywords);
                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword);
                    if ($reply->reply_type == BotReplyType::EXACT_MATCH && $message->value === $keyword) {
                        if ($reply->reply_using_ai == 1 && !empty($client->open_ai_key)) {
                            $message_text = $this->AIReply($keyword, $client);
                        } else {
                            $message_text = $reply->reply_text;
                        }
                        break 2;
                    } elseif ($reply->reply_type == BotReplyType::CONTAINS->value && stripos($message->value, $keyword) !== false) {
                        if ($reply->reply_using_ai == 1 && !empty($client->open_ai_key)) {
                            $message_text = $this->AIReply($keyword, $client);
                        } else {
                            $message_text = $reply->reply_text;
                        }
                        break 2;
                    }
                }
            }
            if ($message_text) {
                $pattern = '/{{\s*([^}]+)\s*}}/';
                preg_match_all($pattern, $message_text, $matches);
                $variables = $matches[1];
                foreach ($variables as $variable) {
                    switch ($variable) {
                        case 'name':
                            $message_text = str_replace('{{' . $variable . '}}', $contact->name, $message_text);
                            break;
                        case 'phone':
                            $message_text = str_replace('{{' . $variable . '}}', $contact->phone, $message_text);
                            break;
                    }
                }

                $reply_message = new Message();
                $reply_message->components = null;
                $reply_message->campaign_id = null;
                $reply_message->contact_id = $contact->id;
                $reply_message->client_id = $client->id;
                $reply_message->value = $message_text;
                $reply_message->message_type = MessageEnum::TEXT;
                $reply_message->message_by = 'bot';
                $reply_message->is_contact_msg = 0;
                $reply_message->status = MessageStatusEnum::SENDING;
                $reply_message->save();
                $message_type = "text";
                // dd($reply_message);
                Log::info('reply_message', [$reply_message]);
                $this->sendWhatsAppMessage($reply_message, $message_type);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Error in BOTReply: ' . $e->getMessage(), [
                'message' => $message,
                'contact' => $contact,
                'client' => $client,
                'exception' => $e
            ]);

            return null; // Optionally, return null or handle the error in another way
        }

        return $reply_message;
    }



    public function AIReply($keyword, $client)
    {
        if (isDemoMode()) {
            return __('demo_mode_notice');
        }
        $message     = null;
        $open_ai_key = $client->open_ai_key;
        $open_ai     = new OpenAi($open_ai_key);
        $use_case    = 'WhatsApp Chat Reply';
        $prompt      = 'Write a ' . $use_case . ' About ' . $keyword;
        $variants    = intval(1);
        $length      = 269 * 1;
        $result      = $open_ai->completion([
            'model'             => 'gpt-3.5-turbo-instruct',
            'prompt'            => $prompt,
            'temperature'       => 0.9,
            'max_tokens'        => (int) $length,
            'frequency_penalty' => 0,
            'presence_penalty'  => 0.6,
            'n'                 => (int) 1,
        ]);
        $result      = json_decode($result);
        if (property_exists($result, 'error')) {
            Log::error('error: ', [$result->error->message]);
        }
        if ($result->choices[0]) {
            $message = $result->choices[0]->text;
        } else {
            Log::error('error: ', ['someting went wrong']);
        }
        return $message;
    }


    private function createReplyMessage($next_node, $contact, $client)
    {
        try {
            $reply_message = new Message();
            $node_type = $next_node['type'];

            switch ($node_type) {
                case 'box-with-title':
                    $message_text = $next_node['data']['reply_text'];
                    $pattern = '/{{\s*([^}]+)\s*}}/';
                    preg_match_all($pattern, $message_text, $matches);
                    $variables = $matches[1];
                    foreach ($variables as $variable) {
                        switch ($variable) {
                            case 'name':
                                $message_text = str_replace('{{' . $variable . '}}', $contact->name, $message_text);
                                break;
                            case 'phone':
                                $message_text = str_replace('{{' . $variable . '}}', $contact->phone, $message_text);
                                break;
                        }
                    }

                    $reply_message->value = $message_text;
                    $reply_message->message_type = MessageEnum::TEXT->value;
                    break;
                case 'node-image':
                    $reply_message->header_image = $next_node['data']['image'];
                    $reply_message->message_type = MessageEnum::IMAGE->value;
                    break;
                case 'box-with-audio':
                    $reply_message->header_audio = $next_node['data']['audio'];
                    $reply_message->message_type = MessageEnum::AUDIO->value;
                    break;
                case 'box-with-video':
                    $reply_message->header_video = $next_node['data']['video'];
                    $reply_message->message_type = MessageEnum::VIDEO->value;
                    break;
                case 'box-with-file':
                    $reply_message->header_document = $next_node['data']['document'];
                    $reply_message->message_type = MessageEnum::DOCUMENT->value;
                    break;
                case 'box-with-template':
                    $reply_message->value = $next_node['data']['template'];
                    $reply_message->message_type = MessageEnum::TEMPLATE->value;
                    break;
                case 'box-with-condition':
                    $reply_message->value = $next_node['data']['condition'];
                    $reply_message->message_type = MessageEnum::CONDITION->value;
                    break;
                case 'box-with-location':
                    $reply_message->header_location = $next_node['data']['location'];
                    $reply_message->message_type = MessageEnum::LOCATION->value;
                    break;
                case 'box-with-list':
                    $reply_message->component_body = $this->decodeAndValidateJson($next_node['data']['list'], 'list');
                    $reply_message->message_type = MessageEnum::INTERACTIVE_LIST->value;
                    break;
                case 'box-with-button':
                    $button_data = $next_node['data']['button'];
                    if (isset($button_data['header_text'])) {
                        $reply_message->header_text = $button_data['header_text'];
                    } elseif (isset($button_data['header_media'])) {
                        $mimeType = $this->getMimeType($button_data['header_media']);
                        if (str_contains($mimeType, 'image')) {
                            $reply_message->header_image = $button_data['header_media'];
                        } elseif (str_contains($mimeType, 'video')) {
                            $reply_message->header_video = $button_data['header_media'];
                        } elseif (str_contains($mimeType, 'audio')) {
                            $reply_message->header_audio = $button_data['header_media'];
                        } elseif (str_contains($mimeType, 'pdf')) {
                            $reply_message->header_document = $button_data['header_media'];
                        }
                    }
                    $reply_message->value = $button_data['body']['text'];
                    if (isset($button_data['footer_text'])) {
                        $reply_message->footer_text = $button_data['footer_text'];
                    }
                    $reply_message->component_buttons = $this->decodeAndValidateJson($button_data, 'button');
                    $buttons = $button_data['action']['buttons'];
                    $formattedButtons = [];
                    foreach ($buttons as $button) {
                        if (!empty($button)) {
                            $formattedButtons[] = [
                                'type' => 'button',
                                'id' => $button['reply']['id'],
                                'text' => $button['reply']['title']
                            ];
                        }
                    }
                    $reply_message->buttons = json_encode($formattedButtons);
                    $reply_message->message_type = MessageEnum::INTERACTIVE_BUTTON->value;
                    break;
                default:
                    throw new \Exception("Unknown node type: $node_type");
            }
            $reply_message->components = null;
            $reply_message->campaign_id = null;
            $reply_message->contact_id = $contact->id;
            $reply_message->client_id = $client->id;
            $reply_message->is_contact_msg = 0;
            $reply_message->status = MessageStatusEnum::SENDING;
            $reply_message->message_by = 'bot';
            $reply_message->save();
            return $reply_message;
        } catch (\Exception $e) {
            // Log::error('reply_message', [$reply_message]);
            // dd($e->getMessage());
            return false;
        }
    }

    private function flowReply($flowId, $contact, $client, $message)
    {
        try {
        // Log::error('flow Reply' . 1);
            if (empty($message)) {
                return null;
            }
            $currentFlowState = ContactFlowState::where('contact_id', $contact->id)->where('flow_id', $flowId)->first();
            $flow = Flow::with('nodes', 'edges')->where('client_id', $client->id)->find($flowId);
            if (!empty($currentFlowState) && !empty($currentFlowState->current_node_id)) {
                $node = FlowNode::where('flow_id', $flowId)->where('client_id', $client->id)->where('node_id', $currentFlowState->current_node_id)->first();
            } else {
                $node = FlowNode::where('flow_id', $flowId)->where('client_id', $client->id)->where('type', 'starter-box')->first();
            }
            $next_node = $this->determineNextNodeReply($flow, $node, $message);
            if (!$next_node['has_next_node']) {
                if ($currentFlowState) {
                    $currentFlowState->delete();
                }
                return true;
            }
            if (isset($next_node) && $next_node['node_id']) {
                ContactFlowState::updateOrCreate(
                    ['contact_id' => $contact->id],
                    [
                        'flow_id' => $flowId,
                        'current_node_id' => $next_node['node_id'],
                        'last_interaction_at' => now(),
                    ]
                );
                $reply = $this->createReplyMessage($next_node, $contact, $client);
                if (!empty($reply)) {
                    // Check if delay_time is less than 1 or null and set to 2 seconds
                    if (empty($next_node['delay_time']) || $next_node['delay_time'] < 1) {
                        $next_node['delay_time'] = 2;
                    }
                    // Apply the delay
                    sleep($next_node['delay_time']);
                    $this->sendWhatsAppMessage($reply, $reply->message_type);;
                }
                if (
                    isset($next_node['type']) &&
                    $next_node['type'] !== 'box-with-condition' &&
                    $next_node['type'] !== 'box-with-list' &&
                    $next_node['type'] !== 'box-with-button'
                ) {
                   
                    $this->flowReply($flowId, $contact, $client, $reply);
                }
                return true;
            }
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }


    private function validateEntity($entity, $errorMessage)
    {
        if (!$entity) {
            throw new \Exception($errorMessage);
        }
    }


    private function decodeAndValidateJson($data, $type)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON format for $type data");
            }
        }
        return [$data];
    }
}
