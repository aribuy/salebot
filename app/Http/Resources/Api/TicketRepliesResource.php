<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketRepliesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                              => (int) $this->id,
            'ticket_id'                       => $this->ticket_id,
            'user_id'                         => $this->user_id,
            'images'                          => getFileLink('80x80', $this->images),
            'viewed'                          => $this->viewed,
            'reply'                           => $this->reply,
            'created_at'                      => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'                      => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
