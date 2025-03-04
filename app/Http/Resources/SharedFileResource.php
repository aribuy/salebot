<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SharedFileResource extends JsonResource
{
    public function toArray($request)
    {
        $media    = '';
        $exploded = [];
        if ($this->header_document) {
            $media = $this->header_document;
        }
        if ($media) {
            $exploded = explode('/', $media);
        }

        return [
            'id'      => $this->id,
            'path'    => $media,
            'name'    => $media ? end($exploded) : '',
            'sent_at' => Carbon::parse($this->created_at)->format('d/m/Y \a\t h:ia'),
        ];
    }
}
