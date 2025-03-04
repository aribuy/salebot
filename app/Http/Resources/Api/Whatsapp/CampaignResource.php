<?php

namespace App\Http\Resources\Api\Whatsapp;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                              => (int) $this->id,
            'campaign_name'                   => $this->campaign_name,
            'campaign_type'                   => $this->campaign_type,
            'created_at'                      => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'                      => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
