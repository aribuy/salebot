<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class TemplateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'template_name'     => 'required|string|max:512',
            'template_category' => 'required|string|in:UTILITY,MARKETING,AUTHENTICATION',
            'header_type'       => 'required|string|in:NONE,TEXT,IMAGE,VIDEO,AUDIO,DOCUMENT',
            'message_body'      => 'required|string|max:1024',
            'footer_text'       => 'nullable|string|max:60',
            'locale'            => 'required|string',
            'header_text'       => 'required_if:header_type,text|string|max:60',
            'header_image'      => 'required_if:header_type,image|image|mimes:jpeg,png,gif|max:5120',
            'header_video'      => 'required_if:header_type,video|mimes:mp4,avi,mov,wmv|file|max:16384',
            'header_document'   => 'required_if:header_type,document|file|mimes:pdf|max:5120',
            'button_type'       => 'required|string|in:NONE,QUICK_REPLY,CTA',
            'type_of_action'    => 'required_if:,CTA|array',
            'button_value'      => 'required_if:button_type,CTA|array',
            'button_text.*'     => [
                'required_if:button_type,QUICK_REPLY',
                'string',
                'max:255'
            ]
        ];
    }
    public function messages()
    {
        return [
            'template_name.required' => 'Template name is required.',
            'template_name.max' => 'Template name can have a maximum of 512 characters.',
            'template_category.required' => 'Template category is required.',
            'template_category.in' => 'Template category must be one of UTILITY, MARKETING, or AUTHENTICATION.',
            'header_type.required' => 'Header type is required.',
            'header_type.in' => 'Header type must be one of none, text, image, video, audio, or document.',
            'message_body.required' => 'Message body is required.',
            'message_body.max' => 'Message body can have a maximum of 1024 characters.',
            'footer_text.max' => 'Footer text can have a maximum of 60 characters.',
            'locale.required' => 'Locale is required.',
            'header_text.required_if' => 'Header text is required when the header type is "text".',
            'header_text.max' => 'Header text can have a maximum of 60 characters.',
            'header_image.required_if' => 'Header image is required when the header type is "image".',
            'header_image.mimes' => 'Header image must be a JPEG, PNG, or GIF.',
            'header_image.max' => 'Header image size must not exceed 5120 KB.',
            'header_video.mimes' => 'Header video must be one of MP4, AVI, MOV, or WMV.',
            'header_video.max' => 'Header video size must not exceed 16384 KB.',
            'header_document.required_if' => 'Header document is required when the header type is "document".',
            'header_document.mimes' => 'Header document must be a PDF.',
            'header_document.max' => 'Header document size must not exceed 5120 KB.',
        ];
    }
}
