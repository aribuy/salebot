<?php

namespace App\Http\Resources\Api\Telegram;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                              => (int) $this->id,
            'title'                           => $this->name,
            'subscriber'                      => $this->subscriber->count(),
            'is_blacklist'                    => $this->subscriber->where('is_blacklist', 1)->count(),
            'created_at'                      => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'                      => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
