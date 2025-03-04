<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class BotReplyRequest extends FormRequest
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
        $rules = [
            'name'       => 'required',
            'reply_type' => 'required',
        ];

        if ($this->input('reply_type') == 'canned_response') {
            $rules['reply_text'] = 'required';
        }
        if (($this->input('reply_type') == 'exact_match' || $this->input('reply_type') == 'contains') && $this->input('reply_using_ai') == 0) {
            $rules['keywords']   = 'required';
            $rules['reply_text'] = 'required';
        }

        return $rules;
    }
}
