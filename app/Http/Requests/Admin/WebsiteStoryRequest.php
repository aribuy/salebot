<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteStoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image'       => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
            'description' => 'nullable',
        ];
    }
}
