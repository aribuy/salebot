<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteTestimonialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|unique:website_testimonials,name,'.$this->id,
            'title'       => 'required',
            'designation' => 'required',
            'description' => 'required',
            'image'       => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
        ];
    }
}
