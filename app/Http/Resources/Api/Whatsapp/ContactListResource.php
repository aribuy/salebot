<?php

namespace App\Http\Resources\Api\Whatsapp;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactListResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id'                      => (int) $this->id,
            'name'                    => $this->name,
            'status'                  => $this->status,
            'created_at'              => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'              => $this->updated_at->format('d-m-Y H:i:s'),

        ];
    }
}