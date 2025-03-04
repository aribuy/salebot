<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CannedResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'reply_text' => $this->reply_text,
        ];
    }
}
