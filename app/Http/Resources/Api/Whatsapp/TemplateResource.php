<?php

namespace App\Http\Resources\Api\Whatsapp;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                      => (int) $this->id,
            'name'                    => $this->name,
            'category'                => $this->category,
            'status'                  => $this->status,
            'created_at'              => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'              => $this->updated_at->format('d-m-Y H:i:s'),
            'contact_list_id'         => $this->whenLoaded('list', function () {
                return $this->list ? $this->list->name : null;
            }),
        ];
    }
}
