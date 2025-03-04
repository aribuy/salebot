<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SharedLinkResource extends JsonResource
{
    public function toArray($request)
    {
        $media = '';
        $type  = '';


        return [
            'id'   => $this->id,
            'path' => $media,
            'type' => $type,
        ];
    }
}
