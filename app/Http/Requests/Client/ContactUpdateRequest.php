<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactUpdateRequest extends FormRequest
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
        $contactId = $this->id;

        return [
            'phone'      => [
                'required',
                'numeric',
                'min:11',
                Rule::unique('contacts', 'phone')->ignore($contactId),
            ],
            'country_id' => 'nullable|exists:countries,id',
            'name' => 'required|string'
        ];
    }
}
