<?php

namespace App\Http\Resources\Api\Telegram;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                              => (int) $this->id,
            'name'                            => $this->name,
            'username'                        => $this->username,
            'is_blacklist'                    => $this->is_blacklist == 1 ? 'Yes' : 'No',
            'is_left_group'                   => $this->is_left_group == 1 ? 'Yes' : 'No',
            'created_at'                      => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'                      => $this->updated_at->format('d-m-Y H:i:s'),
            'group_id'                        => $this->whenLoaded('group', function () {
                return $this->group ? $this->group->name : null;
            }),
        ];
    }
}
