<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SharedMediaResource extends JsonResource
{
    public function toArray($request)
    {
        $media = '';
        $type  = '';
        if ($this->header_image) {
            $media = $this->header_image;
            $type  = 'image';
        } elseif ($this->header_video) {
            $media = $this->header_video;
            $type  = 'video';
        } elseif ($this->header_audio) {
            $media = $this->header_audio;
            $type  = 'audio';
        }

        return [
            'id'   => $this->id,
            'path' => $media,
            'type' => $type,
        ];
    }
}
