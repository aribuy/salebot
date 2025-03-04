<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'receiver_id'          => $this->id,
            'id'                   => $this->id,
            'name'                 => $this->name,
            'phone'                => isDemoMode() ? '+*************' :  @$this->phone,
            'last_conversation_at' => $this->last_conversation_at,
            'image'                => $this->profile_pic,
            'assignee_id'          => nullCheck($this->assignee_id),
            'source'               => $this->type,
        ];
    }
}
