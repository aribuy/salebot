<?php

namespace App\Rules;

use App\Models\Contact;
use App\Models\ContactsList;
use Illuminate\Contracts\Validation\Rule;

class UniqueContactList implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public function passes($attribute, $value)
    {
        return ! ContactsList::where('name', $value)
            ->where('client_id', $this->clientId)
            ->exists();
    }

    public function message()
    {
        return 'the_contact_list_has_already_been_taken';
    }
}
