<?php

namespace App\Rules;

use App\Models\Contact;
use Illuminate\Contracts\Validation\Rule;

class UniquePhoneNumber implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public function passes($attribute, $value)
    {
        return ! Contact::where('phone', $value)
            ->where('client_id', $this->clientId)
            ->exists();
    }

    public function message()
    {
        return 'the_phone_number_has_already_been_taken';
    }
}
