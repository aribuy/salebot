<?php

namespace App\Http\Resources\Api\Whatsapp;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                      => (int) $this->id,
            'name'                    => $this->name,
            'username'                => $this->username,
            'thumbnail'               => getFileLink('80x80', $this->avatar),
            'phone'                   => $this->phone,
            'is_left_group'           => $this->is_left_group,
            'type'                    => $this->type,
            'status'                  => $this->status,
            'is_blacklist'            => $this->is_blacklist,
            'is_verified'             => $this->is_verified,
            'last_conversation_at'    => $this->last_conversation_at,
            'has_conversation'        => $this->has_conversation,
            'has_unread_conversation' => $this->has_unread_conversation,
            'group_chat_id'           => $this->group_chat_id,
            'group_subscriber_id'     => $this->group_subscriber_id,
            'group_id'                => $this->group_id,
            'created_at'              => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'              => $this->updated_at->format('d-m-Y H:i:s'),
            'contact_list_id'         => $this->whenLoaded('list', function () {
                return $this->list ? $this->list->name : null;
            }),
            'country_id'              => $this->whenLoaded('country', function () {
                return $this->country ? $this->country->name : null;
            }),
        ];
    }
}
