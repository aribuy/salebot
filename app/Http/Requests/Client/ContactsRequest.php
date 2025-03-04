<?php

namespace App\Http\Requests\Client;

use App\Rules\UniquePhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Propaganistas\LaravelPhone\PhoneNumber;


class ContactsRequest extends FormRequest
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
            'phone'      => ['required', 'string', 'min:11', new UniquePhoneNumber($client_id)],
            'country_id' => 'nullable|exists:countries,id',
            'name' => 'required|string'

        ];
    }

    public function saving()
    {
        if ($this->phone) {
            $data = preg_replace('/[^0-9]/', '', $this->phone);
            dd($data);
            // $user->phone_national = preg_replace('/[^0-9]/', '', phone($user->phone, $user->phone_country)->formatNational());
            // $user->phone_e164 = phone($user->phone, $user->phone_country)->formatE164();
        }
    }
}
