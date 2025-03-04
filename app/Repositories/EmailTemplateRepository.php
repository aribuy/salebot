<?php

namespace App\Repositories;

use App\Models\EmailTemplate;

class EmailTemplateRepository
{
    public function get($id)
    {
        return EmailTemplate::findorfail($id);
    }

    public function emailTemplate()
    {
        return EmailTemplate::all();
    }

    public function testMail()
    {
        return EmailTemplate::where('identifier', 'test_email')->first();
    }

    public function emailConfirmation()
    {
        return EmailTemplate::where('identifier', 'confirmation_email')->first();
    }

    public function welcomeMail()
    {
        return EmailTemplate::where('identifier', 'welcome_email')->first();
    }


    public function changePass()
    {
        return EmailTemplate::where('email_type', 'password_reset_email')->first();
    }

    public function recoveryMail()
    {
        return EmailTemplate::where('email_type', 'recovery_email')->first();
    }

    public function update($request)
    {
        $id = $request['id'];
        return EmailTemplate::find($id)->update($request);
    }
}
