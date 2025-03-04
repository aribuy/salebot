<?php

namespace App\Http\Resources;

use App\Enums\MessageStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        $header_image     = null;
        $header_text      = null;
        $footer_text      = null;
        $header_audio     = null;
        $header_video     = null;
        $receiver_image   = null;
        $header_document  = null;
        $contact_name     = null;
        $contacts         = $this->contacts ? json_decode($this->contacts) : null;
        $is_html          = false;
        $message          = $this->value;
        if ($this->message_type == 'image' && $this->header_image) {
            $message      = $this->header_image;
            $header_image = $this->header_image;
            $is_image     = true;
        } elseif ($this->message_type == 'video' && $this->header_video) {
            $message      = $this->header_video;
            $header_video = $this->header_video;
            $is_video     = true;
        } elseif ($this->message_type == 'audio' && $this->header_audio) {
            $message      = $this->header_audio;
            $header_audio = $this->header_audio;
            $is_audio     = true;
        } elseif ($this->message_type == 'document' && $this->header_document) {
            $message         = $this->header_document;
            $header_document = $this->header_document;
            $is_file         = true;
        }
        if ($this->header_text || $this->footer_text || $this->header_image || $this->buttons) {
            $is_html = true;
        }

        if ($this->contact->type == "telegram") {
            $receiver_image = isset($this->group_subscriber) && isset($this->group_subscriber->avatar) ? $this->group_subscriber->avatar : static_asset('images/default/user.jpg');
            $contact_name = isset($this->group_subscriber) && isset($this->group_subscriber->name) ? $this->group_subscriber->name : null;
        } else {
            $receiver_image = isset($this->contact->profile_pic) && !empty($this->contact->profile_pic) ? $this->contact->profile_pic : static_asset('images/default/user.jpg');
            $contact_name = isset($this->contact->name) ? $this->contact->name : null;
        }

        $rendered_buttons = [];
        if ($this->buttons) {
            $buttons = json_decode($this->buttons, true);
            // dd($buttons);
            if (is_array($buttons) && count($buttons) > 0) {
                foreach ($buttons as $button) {
                    if (isset($button['parameters']) && is_array($button['parameters']) && count($button['parameters']) > 0) {
                        $params = $button['parameters'][0];
                        $type   = $params['type'] ?? null;
                        if ($type == 'coupon_code') {
                            $rendered_buttons[] = [
                                'type'  => 'button',
                                'text'  => $params[$type],
                                'value' => $params[$type],
                            ];
                        } elseif ($button['sub_type'] == 'URL') {
                            // dd($params);
                            $rendered_buttons[] = [
                                'type'  => 'a',
                                'text'  => $params[$type],
                                'value' => $params[$type],
                            ];
                        } else {
                            $rendered_buttons[] = [
                                'type'  => 'button',
                                'text'  => $params[$type],
                                'value' => $params[$type],
                            ];
                        }

                    } else {

                        $type = $button['type'] ?? 'URL';

                        if ($type = 'URL') {
                            $rendered_buttons[] = [
                                'type'  => 'a',
                                'text'  => @$button['text'],
                                'value' => @$button['url'] ?? [],
                            ];

                        } else {
                            $rendered_buttons[] = [
                                'type'  => 'button',
                                'text'  => @$button['text'],
                                'value' => @$button['url'] ?? [],
                            ];

                        }

                    }
                }
            }
        }


        return [
            'id'              => $this->id,
            'message'         => $this->value,
            'header_document' => @$this->header_document ?? $header_document,
            'header_image'    => @$this->header_image ?? $header_image,
            'header_text'     => $header_text,
            'footer_text'     => $footer_text,
            'header_audio'    => @$this->header_audio ?? $header_audio,
            'header_video'    => @$this->header_video ?? $header_video,
            'header_location' => $this->header_location,
            'contacts'        => $contacts,
            'error'           => $this->error,
            'is_campaign_msg' => $this->is_campaign_msg,
            'type'            => $this->is_contact_msg ? 2 : 1,
            'is_seen'         => $this->status == MessageStatusEnum::READ,
            'is_sent'         => $this->status == MessageStatusEnum::SENT,
            'is_delivered'    => $this->status == MessageStatusEnum::DELIVERED,
            'is_html'         => $is_html,
            'sent_at'         => Carbon::parse($this->created_at)->diffForHumans(),
            'user_image'      => $this->createdBy->profile_pic ?? $this->client->profile_pic,
            'receiver_image'  => $receiver_image,
            'contact_name'    => $contact_name,
            'source'          => $this->contact->type,
            'html'            => [
                'buttons' => $rendered_buttons,
            ],
        ];
    }
}
