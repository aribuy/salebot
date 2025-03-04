<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ClientSetting;

class PhoneNumberIdRule implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public function passes($attribute, $value)
    {
        return !ClientSetting::where('client_id', '!=', $this->clientId)
            ->where('phone_number_id', $value)
            ->exists();
    }

    public function message()
    {
        return __('the_phone_number_id_has_already_been_taken');
    }
}