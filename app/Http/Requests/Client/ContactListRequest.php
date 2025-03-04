<?php

namespace App\Http\Requests\Client;

use App\Rules\UniqueContactList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ContactListRequest extends FormRequest
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
        $client_id = Auth::user()->client->id;
        return [
            'name' => ['required', new UniqueContactList($client_id)],
        ];
    }
}
