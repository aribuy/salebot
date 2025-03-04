<?php

namespace App\Http\Requests\Admin;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
        // Retrieve the selected country ID from the request
        $countryId   = $this->input('country_id');
        // Fetch the corresponding country code from the database
        $countryCode = Country::where('id', $countryId)->value('iso2');
        $rules       = [
            'country_id'   => 'required|exists:countries,id',
            'first_name'   => 'required',
            'last_name'    => 'required',
            'address'      => 'required',
            // 'phone_number' => 'required|unique:users,phone',
            /*'phone_number' => [
                'required',
                'phone:'.($countryCode ?? 'US'),
                'unique:users,phone',
            ],*/
            'email'        => 'required|email|unique:users,email',
            'password'     => ['confirmed', 'required', 'min:6'],
            'images'       => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
            'logo'         => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'country_id.required'   => 'Please select a country.',
            'country_id.exists'     => 'The selected country is invalid.',
            'first_name.required'   => 'First name is required.',
            'last_name.required'    => 'Last name is required.',
            'address.required'      => 'Address is required.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.numeric'  => 'The phone number must be a valid number.',
            'phone_number.unique'   => 'The phone number has already been taken.',
            'phone_number.phone'    => 'The phone number is not valid for the selected country.',
            'email.required'        => 'Email is required.',
            'email.email'           => 'Email must be a valid email address.',
            'email.unique'          => 'The email has already been taken.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 6 characters.',
            'images.mimes'          => 'Images must be a file of type: jpg, jpeg, png, webp.',
            'images.max'            => 'Images must not be greater than 5120 kilobytes.',
            'logo.mimes'            => 'Logo must be a file of type: jpg, jpeg, png, webp.',
            'logo.max'              => 'Logo must not be greater than 5120 kilobytes.',
        ];
    }
}
