<?php

// app/Rules/AppIdRule.php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ClientSetting;

class AppIdRule implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public function passes($attribute, $value)
    {
        return !ClientSetting::where('client_id', '!=', $this->clientId)
            ->where('app_id', $value)
            ->exists();
    }

    public function message()
    {
        return __('the_app_id_has_already_been_taken');
    }
}
