<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstallRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'host'            => 'required_without:user_details',
            'db_user'         => 'required_without:user_details',
            'db_name'         => 'required_without:user_details',
            'activation_code' => 'required_without:user_details',
            'first_name'      => 'required_with:user_details',
            'last_name'       => 'required_with:user_details',
            'email'           => 'required_with:user_details|email',
            'password'        => 'required_with:user_details|min:6',
        ];
    }
}
