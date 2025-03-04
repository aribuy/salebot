<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                              => (int) $this->id,
            'name'                            => $this->user->name ?? '',
            'email'                           => $this->user->email ?? '',
            'phone'                           => countryCode($this->user->phone_country_id).$this->user->phone ?? '',
            'last_login'                      => $this->user->lastActivity ? $this->user->lastActivity->created_at->diffForHumans() : '',
            'status'                          => $this->user->status ?? '',
            'created_at'                      => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'                      => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
