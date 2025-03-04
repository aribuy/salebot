<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                              => (int) $this->id,
            'ticket_id'                       => $this->ticket_id,
            'subject'                         => $this->subject,
            'priority'                        => $this->priority,
            'status'                          => $this->status,
            'created_at'                      => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'                      => $this->updated_at->format('d-m-Y H:i:s'),
            'departments'                     => $this->whenLoaded('department', function () {
                return $this->department ? $this->department->title : null;
            }),
        ];
    }
}
