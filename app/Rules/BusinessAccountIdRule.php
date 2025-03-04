<?php
// app/Rules/BusinessAccountIdRule.php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ClientSetting;

class BusinessAccountIdRule implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public function passes($attribute, $value)
    {
        return !ClientSetting::where('client_id', '!=', $this->clientId)
            ->where('business_account_id', $value)
            ->exists();
    }

    public function message()
    {
        return __('the_business_account_id_has_already_been_taken');
    }
}
