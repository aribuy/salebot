<?php

namespace App\Traits;

use App\Models\Client;
use App\Models\Conversation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait CommonTrait
{
    public function conversationUpdate($client_id, $contact_id)
    {
        if ($client_id === null || $contact_id === null) {
            return;
        }
        $client                 = Client::find($client_id);
        if (! $client || ! $client->activeSubscription) {
            return;
        }
        $subscription           = $client->activeSubscription;
        $conversation_remaining = $subscription->conversation_remaining ?? 0;
        $last_24_hours          = Carbon::now()->subHours(24);
        // $existingConversation = Conversation::where('client_id', $client_id)
        // ->where('contact_id', $contact_id)
        // ->whereHas('contact', function ($query) use ($last_24_hours) {
        //     $query->where('last_conversation_at', '>', $last_24_hours);
        // })
        // ->first();
        $existingConversation   = Conversation::select('conversations.*', 'contacts.last_conversation_at')
            ->where('conversations.client_id', $client_id)
            ->join('contacts', 'contacts.id', '=', 'conversations.contact_id')
            ->where('conversations.contact_id', $contact_id)
            // ->where('created_at', '>', $last_24_hours)
            ->where('contacts.last_conversation_at', '>', $last_24_hours)
            ->first();
        if ($existingConversation) {
            return $existingConversation->id;
        }
        if ($conversation_remaining > 0) {
            try {
                $conversation_remaining--;
                $subscription->conversation_remaining = $conversation_remaining;
                $subscription->save();
                $conversation                         = new Conversation();
                $conversation->unique_id              = Str::upper(uniqid());
                $conversation->contact_id             = $contact_id;
                $conversation->client_id              = $client_id;
                $conversation->subscription_id        = $subscription->id;
                $conversation->save();

                return $conversation->id;
            } catch (\Exception $e) {
                \Log::error('Error occurred while updating conversation: '.$e->getMessage());

                return;
            }
        }

        return null;
    }

    
    public function isEmail($email)
    {
        $email= trim($email);
        $is_valid=0;
        /***Validation check***/
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        if (preg_match($pattern, $email) === 1) {
            $is_valid=1;
        }
        return $is_valid;
    }

    public function isPhoneNumber($phone)
    {    
        $is_valid=0;
        if(preg_match("#\+\d{7}#",$phone)===1)
            $is_valid=1; 
        return $is_valid;
    }
    
}
