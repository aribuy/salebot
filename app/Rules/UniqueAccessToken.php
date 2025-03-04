<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ClientSetting;

class UniqueAccessToken implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public function passes($attribute, $value)
    {
        // Check if the access token is unique for the given client ID
        return !ClientSetting::where('client_id', '!=',$this->clientId)
            ->where('access_token', $value)
            ->exists();
    }

    public function message()
    {
        return __('the_access_token_has_already_been_taken');
    }
}
