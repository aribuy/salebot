<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CampaignsRequest extends FormRequest
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
            'campaign_name' => 'required|string',
            'template_id' => 'required|exists:templates,id',
            'variables' => 'nullable|string',
            'variables_match' => 'nullable|string',
            'url_link' => 'nullable',
            'send_scheduled' => 'nullable',
            'schedule_time' => 'required_if:send_scheduled,1',
            'contact_list_id' => 'nullable|array',
            'segment_id' => 'nullable|array',
        ];
    }
    
}
