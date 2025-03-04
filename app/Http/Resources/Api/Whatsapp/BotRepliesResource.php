<?php

namespace App\Http\Resources\Api\Whatsapp;

use Illuminate\Http\Resources\Json\JsonResource;

class BotRepliesResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id'                      => (int) $this->id,
            'name'                    => $this->name,
            'reply_type'              => $this->reply_type,
            'reply_using_ai'          => $this->reply_using_ai,
            'reply_text'              => $this->reply_text,
            'keywords'                => $this->keywords,
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
        ];
    }
}