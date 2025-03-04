<?php

namespace App\Repositories\Client;
use Carbon\Carbon;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use App\Models\Segment;
use App\Models\Campaign;
use App\Models\Template;
use App\Enums\StatusEnum;
use App\Models\Attribute;
use App\Enums\MessageEnum;
use App\Traits\ImageTrait;
use App\Traits\CommonTrait;
use App\Models\ContactsList;
use App\Models\Subscription;
use App\Traits\RepoResponse;
use App\Traits\BotReplyTrait;
use App\Traits\TelegramTrait;
use App\Traits\WhatsAppTrait;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendWhatsAppCampaignMessageJob;
use Illuminate\Support\Facades\Auth;

class WaCampaignRepository
{
    use CommonTrait, ImageTrait, RepoResponse, TelegramTrait, WhatsAppTrait,BotReplyTrait;

    private $model;

    private $contact;

    private $template;

    private $segment;

    private $contact_list;

    private $country;

    private $message;

    private $attribute;

    public function __construct(
        Campaign $model,
        Contact $contact,
        Template $template,
        Segment $segment,
        ContactsList $contact_list,
        Country $country,
        Message $message,
        Attribute $attribute,
    ) {
        $this->model        = $model;
        $this->contact      = $contact;
        $this->template     = $template;
        $this->segment      = $segment;
        $this->contact_list = $contact_list;
        $this->country      = $country;
        $this->message      = $message;
        $this->attribute    = $attribute;
    }

    public function all()
    {
        return Campaign::latest()->withPermission()->paginate(setting('pagination'));
    }

    public function activeSegments()
    {
        return Campaign::where('status', 1)->withPermission()->get();
    } 

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $campaignRemaining       = auth()->user()->activeSubscription->campaign_remaining;
            $conversationRemaining   = auth()->user()->activeSubscription->conversation_remaining;
            if ($campaignRemaining <= 0) {
                return $this->formatResponse(false, __('insufficient_campaigns_limit'), 'client.whatsapp.campaigns.index', []);
            }
            if ($conversationRemaining <= 0) {
                return $this->formatResponse(false, __('insufficient_conversation_limit'), 'client.whatsapp.campaigns.index', []);
            }
            $media_url               = null;
            if ($request->hasFile('image')) {
                $response  = $this->saveImage($request->image);
                $media_url = $response['images'];
                $media_url = getFileLink('original_image', $media_url);
            } elseif ($request->hasFile('document')) {
                $media_url = asset('public/'.$this->saveFile($request->document, 'pdf', false));
            } elseif ($request->hasFile('video')) {
                $media_url = asset('public/'.$this->saveFile($request->video, 'mp4', false));
            } elseif ($request->hasFile('audio')) {
                $media_url = asset('public/'.$this->saveFile($request->audio, 'mp3', false));
            }
            $campaign                = new $this->model;
            $campaign->campaign_name = $request->campaign_name;
            $campaign->client_id     = Auth::user()->client_id;
            $campaign->template_id   = $request->template_id;
            $campaign->campaign_type = TypeEnum::WHATSAPP;
            $campaign->media_url     = $media_url;
            $campaign->url_link      = $request->url_link;
            if ($request->contact_list_id !== 'all' && isset($request->contact_list_id)) {
                $contactListIds = is_array($request->contact_list_id) ? $request->contact_list_id : [$request->contact_list_id];
                $campaign->contact_list_ids = $contactListIds;
            }
            if ($request->segment_id !== 'all' && isset($request->segment_id)) {
                $segmentIds = is_array($request->segment_id) ? $request->segment_id : [$request->segment_id];
                $campaign->segment_ids = $segmentIds;
            }
            $campaign->save();


            $campaign_remaining      = $campaignRemaining - 1;
            Subscription::where('client_id', auth()->user()->client_id)->where('status', 1)->update(['campaign_remaining' => $campaign_remaining]);
            $contacts                = $this->contact->select('contacts.*')
                ->where('contacts.status', 1)
                ->where('contacts.is_blacklist', 0)
                ->where('type', TypeEnum::WHATSAPP)
                ->whereNotNull('phone')->where('client_id', Auth::user()->client->id);
            if (!empty($request->contact_id)) {
                $contacts = $contacts->where('id', $request->contact_id);
            }
            if (!empty($request->contact_list_id) && $request->contact_list_id !== 'all') {
                $contactListIds = is_array($request->contact_list_id) ? $request->contact_list_id : [$request->contact_list_id];
                $contacts = $contacts->join('contact_relation_lists', 'contact_relation_lists.contact_id', '=', 'contacts.id')
                    ->whereIn('contact_relation_lists.contact_list_id', $contactListIds);
            }
            
            if (!empty($request->segment_id) && $request->segment_id !== 'all') {
                $segmentIds = is_array($request->segment_id) ? $request->segment_id : [$request->segment_id];
                $contacts = $contacts->join('contact_relation_segments', 'contact_relation_segments.contact_id', '=', 'contacts.id')
                    ->whereIn('contact_relation_segments.segment_id', $segmentIds);
            }
            $contacts                = $contacts->distinct('contacts.id');
            $contacts                = $contacts->get();
            $template                = $this->template->active()->findOrFail($request->template_id);
            $body_values             = $request->body_values;
            $body_matchs             = $request->body_matchs;
            $button_values           = $request->button_values;
            $button_matchs           = $request->button_matchs;
            foreach ($contacts as $key => $contact) {
                $content                    = null;
                $header_text                = null;
                $header_image               = null;
                $header_document            = null;
                $header_video               = null;
                $header_audio               = null;
                $footer_text                = null;
                $buttons                    = [];
                $template_components        = $template->components;
                $component_body             = [];
                $component_header           = [];
                $component_buttons          = [];
                foreach ($template_components as $component) {
                    if ($component['type'] == 'HEADER') {
                        if ($component['format'] == 'TEXT') {
                            $header_text = $component['text'];
                        } elseif ($component['format'] == 'DOCUMENT') {
                            $component_header[] = [
                                'type'     => 'document',
                                'document' => [
                                    'link' => $campaign->media_url,
                                ],
                            ];
                            $header_document    = $campaign->media_url;
                        } elseif ($component['format'] == 'IMAGE') {
                            $component_header[] = [
                                'type'  => 'image',
                                'image' => [
                                    'link' => $campaign->media_url,
                                ],
                            ];
                            $header_image       = $campaign->media_url;
                        } elseif ($component['format'] == 'VIDEO') {
                            $component_header[] = [
                                'type'  => 'video',
                                'video' => [
                                    'link' => $campaign->media_url,
                                ],
                            ];
                            $header_video       = $campaign->media_url;
                        } elseif ($component['format'] == 'AUDIO') {
                            $component_header[] = [
                                'type'  => 'audio',
                                'audio' => [
                                    'link' => $campaign->media_url,
                                ],
                            ];
                            $header_audio       = $campaign->media_url;
                        }
                    } elseif ($component['type'] == 'BODY') {
                        $content        = $component['text'];
                        $parameters     = [];
                        if (isset($body_values)) {
                            foreach ($body_matchs as $_key => $value) {
                                if ($value == 'input_value') {
                                    $parameters[] = [
                                        'type' => 'text',
                                        'text' => $body_values[$_key],
                                    ];
                                    $content      = str_replace('{{' . $_key . '}}', $body_values[$_key], $content);
                                } elseif ($value == 'contact_name') {
                                    $parameters[] = [
                                        'type' => 'text',
                                        'text' => $contact->name,
                                    ];
                                    $content      = str_replace('{{' . $_key . '}}', $contact->name, $content);
                                } elseif ($value == 'contact_phone') {
                                    $parameters[] = [
                                        'type' => 'text',
                                        'text' => $contact->phone,
                                    ];
                                    $content      = str_replace('{{' . $_key . '}}', $contact->phone, $content);
                                } else {
                                    $parameters[] = [
                                        'type' => 'text',
                                        'text' => '',
                                    ];
                                }
                            }
                        }
                        $component_body = $parameters;
                    } elseif ($component['type'] == 'BUTTONS') {
                        $buttons_for_chat  = [];
                        $buttons_compo     = [];
                        foreach ($component['buttons'] as $btn_key => $button) {
                            $i = 0;
                            if (isset($button_matchs) && (($button['type'] == 'URL' && stripos($button['url'], '{{') !== false) || ($button['type'] == 'COPY_CODE'))) {
                                foreach ($button_matchs as $btn_key => $value) {
                                    $parameters               = [];
                                    $button_content           = '';
                                    $buttonData               = [
                                        'type'       => 'button',
                                        'sub_type'   => $button['type'],
                                        'index'      => $i . '',
                                        'parameters' => $parameters,
                                    ];
                                    $type                     = 'text';
                                    if ($button['type'] == 'COPY_CODE') {
                                        $type           = 'coupon_code';
                                        $button_content = $button_values[$btn_key];
                                    } else {
                                        $button_content = $button['url'];
                                    }
                                    if ($value == 'input_value') {

                                        $parameters[]   = [
                                            'type' => $type,
                                            $type  => $button_values[$btn_key],
                                        ];
                                        $button_content = str_replace('{{' . $btn_key . '}}', $button_values[$btn_key], $button_content);
                                    } elseif ($value == 'contact_name') {
                                        $parameters[]   = [
                                            'type' => $type,
                                            $type  => $contact->name,
                                        ];
                                        $button_content = $button['url'];

                                        $button_content = str_replace('{{' . $btn_key . '}}', $contact->name, $button_content);
                                    } elseif ($value == 'contact_phone') {
                                        $parameters[]   = [
                                            'type' => $type,
                                            $type  => $contact->phone,
                                        ];
                                        $button_content = $button['url'];
                                        $button_content = str_replace('{{' . $btn_key . '}}', $contact->phone, $button_content);
                                    }
                                    unset($button['example']);
                                    $buttonData['parameters'] = $parameters;
                                    $buttons_compo[]          = $buttonData;

                                    $buttons_for_chat[]       = [
                                        'type' => $button['type'],
                                        'text' => $button['text'],
                                        'url'  => $button_content,
                                    ];
                                    $i++;
                                }
                            } else {
                                unset($button['example']);
                                $buttonData         = [
                                    'type'       => 'button',
                                    'sub_type'   => $button['type'],
                                    'index'      => $i . '',
                                    'parameters' => $button,
                                ];
                                $buttons_for_chat[] = $button;
                            }
                        } 

                        $component_buttons = $buttons_compo;
                        $buttons           = $buttons_for_chat;
                    } elseif ($component['type'] == 'FOOTER') {
                        $footer_text = $component['text'];
                    }
                }
                $result                     = array_merge($component_header, $component_body, $component_buttons);
                $jsonResult                 = json_encode($result, JSON_UNESCAPED_UNICODE);
                $message                    = new $this->message();
                $message->contact_id        = $contact->id;
                $message->template_id       = $template->id;
                $message->client_id         = Auth::user()->client_id;
                $message->header_text       = $header_text;
                $message->footer_text       = $footer_text;
                $message->header_image      = $header_image;
                $message->header_audio      = $header_audio;
                $message->header_video      = $header_video;
                $message->header_location   = $request->header_location;
                $message->header_document   = $header_document;
                $message->buttons           = json_encode($buttons);
                $message->value             = $content;
                $message->error             = null;
                $message->message_type      = MessageEnum::TEXT;
                $message->status            = MessageStatusEnum::SCHEDULED;
                $scheduleTime               = Carbon::now();
                //TimeZone Base
                if ($request->send_scheduled == 1) {
                    $clientTimezone  = Auth::user()->client->timezone ?? config('app.timezone'); // Asia/Dhaka
                    $systemTimezone  = config('app.timezone'); // Asia/Tokyo
                    $scheduleTimeStr = $request->schedule_time; // 2024-04-01 15:5
                    // Assuming $schedule_time is in the format 'Y-m-d H:i'
                    $scheduleTime    = Carbon::createFromFormat('Y-m-d H:i', $scheduleTimeStr, $clientTimezone)
                        ->setTimezone($systemTimezone);
                } else {
                    $scheduleTime = Carbon::now();
                }
                $message->schedule_at       = $scheduleTime;
                $message->components        = $jsonResult;
                $message->component_header  = json_encode($component_header);
                $message->component_body    = json_encode($component_body);
                $message->component_buttons = json_encode($component_buttons);
                $message->campaign_id       = $campaign->id;
                $message->is_campaign_msg   = 1;
                $message->save();
                // Increment the total_contact attribute of the corresponding campaign by 1
                $campaign->total_contact += 1;
                $campaign->save();
                $this->conversationUpdate(Auth::user()->client_id, $contact->id);
            }
            DB::commit();

            return $this->formatResponse(true, __('created_successfully'), 'client.whatsapp.campaigns.index', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            \Log::info('WhatsApp Campaign Error', [$e->getMessage()]);

            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.whatsapp.campaigns.index', []);
        }
    }

    public function ContactTemplateStore($request)
    {
        DB::beginTransaction();
        try {
            $conversation_remaining     = auth()->user()->activeSubscription->conversation_remaining;
            if ($conversation_remaining <= 0) {
                return $this->formatResponse(false, __('insufficient_conversation_limit'), 'client.chat.index', []);
            }
            $media_url                  = null;
            if ($request->hasFile('image')) {
                $response  = $this->saveImage($request->image);
                $media_url = $response['images'];
                $media_url = getFileLink('original_image', $media_url);
            } elseif ($request->hasFile('document')) {
                $media_url = asset('public/'.$this->saveFile($request->document, 'pdf', false));
            } elseif ($request->hasFile('video')) {
                $media_url = asset('public/'.$this->saveFile($request->video, 'mp4', false));
            } elseif ($request->hasFile('audio')) {
                $media_url = asset('public/'.$this->saveFile($request->audio, 'mp3', false));
            }
            $contact                    = $this->contact->findOrFail($request->contact_id);
            $template                   = $this->template->active()->findOrFail($request->template_id);
            $body_values                = $request->body_values;
            $body_matchs                = $request->body_matchs;
            $button_values              = $request->button_values;
            $button_matchs              = $request->button_matchs;
            $content                    = null;
            $header_text                = null;
            $header_image               = null;
            $header_document            = null;
            $header_video               = null;
            $header_audio               = null;
            $footer_text                = null;
            $buttons                    = [];
            $template_components        = $template->components;
            $component_body             = [];
            $component_header           = [];
            $component_buttons          = [];
            foreach ($template_components as $component) {
                if ($component['type'] == 'HEADER') {
                    if ($component['format'] == 'TEXT') {
                        $header_text = $component['text'];
                    } elseif ($component['format'] == 'DOCUMENT') {
                        $component_header[] = [
                            'type'     => 'document',
                            'document' => [
                                'link' => $media_url,
                            ],
                        ];
                        $header_document    = $media_url;
                    } elseif ($component['format'] == 'IMAGE') {
                        $component_header[] = [
                            'type'  => 'image',
                            'image' => [
                                'link' => $media_url,
                            ],
                        ];
                        $header_image       = $media_url;
                    } elseif ($component['format'] == 'VIDEO') {
                        $component_header[] = [
                            'type'  => 'video',
                            'video' => [
                                'link' => $media_url,
                            ],
                        ];
                        $header_video       = $media_url;
                    } elseif ($component['format'] == 'AUDIO') {
                        $component_header[] = [
                            'type'  => 'audio',
                            'audio' => [
                                'link' => $media_url,
                            ],
                        ];
                        $header_audio       = $media_url;
                    }
                } elseif ($component['type'] == 'BODY') {
                    $content        = $component['text'];
                    $parameters     = [];
                    if (isset($body_values)) {
                        foreach ($body_matchs as $_key => $value) {
                            if ($value == 'input_value') {
                                $parameters[] = [
                                    'type' => 'text',
                                    'text' => $body_values[$_key],
                                ];
                                $content      = str_replace('{{' . $_key . '}}', $body_values[$_key], $content);
                            } elseif ($value == 'contact_name') {
                                $parameters[] = [
                                    'type' => 'text',
                                    'text' => $contact->name,
                                ];
                                $content      = str_replace('{{' . $_key . '}}', $contact->name, $content);
                            } elseif ($value == 'contact_phone') {
                                $parameters[] = [
                                    'type' => 'text',
                                    'text' => $contact->phone,
                                ];
                                $content      = str_replace('{{' . $_key . '}}', $contact->phone, $content);
                            } else {
                                $parameters[] = [
                                    'type' => 'text',
                                    'text' => '',
                                ];
                            }
                        }
                    }
                    $component_body = $parameters;
                } elseif ($component['type'] == 'BUTTONS') {
                    $buttons_for_chat  = [];
                    $buttons_compo     = [];
                    foreach ($component['buttons'] as $btn_key => $button) {
                        $i = 0;
                        if (isset($button_matchs) && (($button['type'] == 'URL' && stripos($button['url'], '{{') !== false) || ($button['type'] == 'COPY_CODE'))) {
                            foreach ($button_matchs as $btn_key => $value) {
                                $parameters               = [];
                                $button_content           = '';
                                $buttonData               = [
                                    'type'       => 'button',
                                    'sub_type'   => $button['type'],
                                    'index'      => $i . '',
                                    'parameters' => $parameters,
                                ];
                                $type                     = 'text';
                                if ($button['type'] == 'COPY_CODE') {
                                    $type           = 'coupon_code';
                                    $button_content = $button_values[$btn_key];
                                } else {
                                    $button_content = $button['url'];
                                }
                                if ($value == 'input_value') {

                                    $parameters[]   = [
                                        'type' => $type,
                                        $type  => $button_values[$btn_key],
                                    ];
                                    $button_content = str_replace('{{' . $btn_key . '}}', $button_values[$btn_key], $button_content);
                                } elseif ($value == 'contact_name') {
                                    $parameters[]   = [
                                        'type' => $type,
                                        $type  => $contact->name,
                                    ];
                                    $button_content = $button['url'];

                                    $button_content = str_replace('{{' . $btn_key . '}}', $contact->name, $button_content);
                                } elseif ($value == 'contact_phone') {
                                    $parameters[]   = [
                                        'type' => $type,
                                        $type  => $contact->phone,
                                    ];
                                    $button_content = $button['url'];
                                    $button_content = str_replace('{{' . $btn_key . '}}', $contact->phone, $button_content);
                                }
                                unset($button['example']);
                                $buttonData['parameters'] = $parameters;
                                $buttons_compo[]          = $buttonData;

                                $buttons_for_chat[]       = [
                                    'type' => $button['type'],
                                    'text' => $button['text'],
                                    'url'  => $button_content,
                                ];
                                $i++;
                            }
                        } else {
                            unset($button['example']);
                            $buttonData         = [
                                'type'       => 'button',
                                'sub_type'   => $button['type'],
                                'index'      => $i . '',
                                'parameters' => $button,
                            ];
                            $buttons_for_chat[] = $button;
                        }
                    }

                    $component_buttons = $buttons_compo;
                    $buttons           = $buttons_for_chat;
                } elseif ($component['type'] == 'FOOTER') {
                    $footer_text = $component['text'];
                }
            }
            $result                     = array_merge($component_header, $component_body, $component_buttons);
            $jsonResult                 = json_encode($result, JSON_UNESCAPED_UNICODE);
            $message                    = new $this->message();
            $message->contact_id        = $contact->id;
            $message->template_id       = $template->id;
            $message->client_id         = Auth::user()->client->id;
            $message->header_text       = $header_text;
            $message->footer_text       = $footer_text;
            $message->header_image      = $header_image;
            $message->header_audio      = $header_audio;
            $message->header_video      = $header_video;
            $message->header_location   = $request->header_location;
            $message->header_document   = $header_document;
            $message->buttons           = json_encode($buttons);
            $message->value             = $content;
            $message->error             = null;
            $message->message_type      = MessageEnum::TEXT;
            $message->status            = MessageStatusEnum::SCHEDULED;
            $scheduleTime               = Carbon::now();
            $message->schedule_at       = $scheduleTime;
            $message->components        = $jsonResult;
            $message->component_header  = json_encode($component_header);
            $message->component_body    = json_encode($component_body);
            $message->component_buttons = json_encode($component_buttons);
            $message->campaign_id       = null;
            $message->is_campaign_msg   = 1;
            $message->save();
            $conversation_remaining     = $conversation_remaining - 1;
            Subscription::where('client_id', auth()->user()->client_id)->where('status', 1)->update(['conversation_remaining' => $conversation_remaining]);
            $this->conversationUpdate(Auth::user()->client_id, $contact->id);
            SendWhatsAppCampaignMessageJob::dispatch($message);
            DB::commit();
            return $this->formatResponse(true, __('created_successfully'), 'client.chat.index', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }

            \Log::info('Send Template', [$e->getMessage()]);

            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.chat.index', []);
        }
    }

    public function find($id)
    {
        return Campaign::find($id);
    }

    public function sendScheduleMessage($request)
    {
        $limit    = setting('message_limit') ? (int) setting('message_limit') : 100;
        // Retrieve scheduled messages that are ready to be sent
        $messages = $this->message
            ->where('status', MessageStatusEnum::SCHEDULED)
            ->where('schedule_at', '<', now())
            ->whereHas('campaign', function ($query) {
                $query->where('status', StatusEnum::ACTIVE)
                    ->orWhere('status', StatusEnum::PROCESSED)
                    ->orWhere('status', StatusEnum::EXECUTED);
            })
            ->take($limit)
            ->get();

        // Process each scheduled message
        foreach ($messages as $message) {
            if ($message->source == TypeEnum::WHATSAPP) {
                SendWhatsAppCampaignMessageJob::dispatch($message);
            } else {
                
                $this->sendTelegramMessage($message, $message->message_type);
            }
        }
        $this->updateCampaignStatus();
        return true;
    }

    private function updateCampaignStatus()
    {
        $campaigns = Campaign::whereIn('status', [StatusEnum::ACTIVE, StatusEnum::PROCESSED])->get();
        foreach ($campaigns as $campaign) {
            $nonScheduledMessagesExist = $campaign->messages()->where('status', '!=', MessageStatusEnum::SCHEDULED)->exists();
            if ($nonScheduledMessagesExist) {
                DB::table('campaigns')->where('id', $campaign->id)->update([
                    'status' => StatusEnum::EXECUTED,
                ]);
            }
        }
    }

    public function statusUpdate($request, $id)
    {
        try {
            $campaign         = $this->model->find($id);
            $campaign->status = $request->status;
            $campaign->save();

            return $this->formatResponse(true, __('updated_successfully'), 'client.whatsapp.campaigns.index', $campaign);
        } catch (\Throwable $e) {
            return $this->formatResponse(false, $e->getMessage(), 'client.whatsapp.campaigns.index', []);
        }
    }

    //Resend
    public function resend($request)
    {
        DB::beginTransaction();
        try {
            $subscription          = auth()->user()->activeSubscription;
            $conversationRemaining = $subscription->conversation_remaining;
            if ($conversationRemaining <= 0) {
                return $this->formatResponse(false, __('insufficient_conversation_limit'), 'client.whatsapp.campaigns.index', []);
            }
            $campaign              = $this->model->findOrFail($request->campaign_id);
            $campaign->status      = StatusEnum::ACTIVE;
            $campaign->save();
            $messages              = $this->message->where('campaign_id', $campaign->id);
            if ($request->resend_option == 'did_not_read') {
                $messages = $messages->where('status', MessageStatusEnum::DELIVERED);
            } elseif ($request->resend_option == 'not_delivered') {
                $messages = $messages->where('status', MessageStatusEnum::SENT);
            } else {
                $messages = $messages->where('status', MessageStatusEnum::FAILED);
            }
            $messages              = $messages->get();
            if ($messages->isEmpty()) {
                return $this->formatResponse(false, __('no_messages_found_to_resend'), 'client.whatsapp.campaigns.index', []);
            }
            foreach ($messages as $message) {
                $newMessage                    = new $this->message();
                $newMessage->contact_id        = $message->contact_id;
                $newMessage->template_id       = $message->template_id;
                $newMessage->client_id         = $message->client_id;
                $newMessage->header_text       = $message->header_text;
                $newMessage->footer_text       = $message->footer_text;
                $newMessage->header_image      = $message->header_image;
                $newMessage->header_audio      = $message->header_audio;
                $newMessage->header_video      = $message->header_video;
                $newMessage->header_location   = $message->header_location;
                $newMessage->header_document   = $message->header_document;
                $newMessage->buttons           = $message->buttons;
                $newMessage->value             = $message->value;
                $newMessage->error             = null;
                $newMessage->message_type      = $message->message_type;
                $newMessage->status            = MessageStatusEnum::SCHEDULED;
                $newMessage->schedule_at       = Carbon::now();
                $newMessage->components        = $message->components;
                $newMessage->component_header  = $message->component_header;
                $newMessage->component_body    = $message->component_body;
                $newMessage->component_buttons = $message->component_buttons;
                $newMessage->campaign_id       = $message->campaign_id;
                $newMessage->is_campaign_msg   = 1;
                $newMessage->save();
                $campaign->total_contact += 1;
                $campaign->save();
                $this->conversationUpdate(Auth::user()->client->id, $newMessage->contact_id);
            }
            DB::commit();
            return $this->formatResponse(true, __('created_successfully'), 'client.whatsapp.campaigns.index', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            \Log::info('Resend Camapign', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.whatsapp.campaigns.index', []);
        }
    }
 
    public function campaignCountContact($request)
    {
        $contacts = $this->contact->select('contacts.*')
            ->active()
            ->where('contacts.is_blacklist', 0)
            ->where('type', TypeEnum::WHATSAPP)
            ->whereNotNull('phone')
            ->where('client_id', Auth::user()->client->id);
        if (!empty($request->contact_list_id)) {
            $contactListIds = is_array($request->contact_list_id) ? $request->contact_list_id : [$request->contact_list_id];
            $contacts = $contacts->join('contact_relation_lists', 'contact_relation_lists.contact_id', '=', 'contacts.id')
                ->whereIn('contact_relation_lists.contact_list_id', $contactListIds);
        }
        if (!empty($request->segment_id)) {
            $segmentIds = is_array($request->segment_id) ? $request->segment_id : [$request->segment_id];
            $contacts = $contacts->join('contact_relation_segments', 'contact_relation_segments.contact_id', '=', 'contacts.id')
                ->whereIn('contact_relation_segments.segment_id', $segmentIds);
        }
        // Get the count of distinct contacts
        $contacts = $contacts->distinct('contacts.id');
        $count = $contacts->count();
        return $count;
    }
    
    
    
}
