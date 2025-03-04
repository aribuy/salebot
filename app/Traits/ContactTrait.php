<?php
namespace App\Traits;
use App\Models\Contact;
use App\Models\Segment;
use App\Traits\CommonTrait;
use App\Models\ContactsList;
use App\Models\ContactRelationList;
use App\Models\ContactRelationSegments;


trait ContactTrait
{
    use SendNotification, CommonTrait;

    public $facebook_api = 'https://graph.facebook.com/v19.0/';

    private function getOrCreateContact($phone, $name, $client) {
        // Attempt to find an existing contact or create a new one
        $contact = $this->contact->where('client_id', $client->id)
            ->where(function ($query) use ($phone) {
                $query->where('phone', $phone)->orWhere('phone', "+" . $phone);
            })
            ->first();
    
        if (!$contact) {
            $contact = new Contact();
            $contact->name = $name;
            $contact->phone = $phone;
            $contact->client_id = $client->id;
            $contact->country_id = $this->whatsappService->extractCountryCode($phone);
            $contact->has_conversation = 1;
            $contact->is_verified = 1;
            $contact->has_unread_conversation = 1;
            $contact->last_conversation_at = now();
            $contact->status = 1;
            $contact->save();
        }
        return $contact;
    }
    
    private function getOrCreateContactList($client) {
        return ContactsList::firstOrCreate([
            'client_id' => $client->id,
            'name' => 'Uncategorized',
        ]);
    }
    
    private function getOrCreateDefaultSegment($client) {
        return Segment::firstOrCreate([
            'client_id' => $client->id,
            'title' => 'Default',
        ]);
    }
    
    private function establishContactSegmentRelations($contact, $defaultSegment) {

        ContactRelationSegments::firstOrCreate([
            'contact_id' => $contact->id,
            'segment_id' => $defaultSegment->id,
        ]);
    }

    private function establishContactListRelations($contact, $contactList) {
        ContactRelationList::firstOrCreate([
            'contact_id' => $contact->id,
            'contact_list_id' => $contactList->id,
        ]);
    
        
    }
    
    private function updateContactStatus($contact) {
        $contact->update([
            'is_verified' => 1,
            'has_conversation' => 1,
            'has_unread_conversation' => 1,
            'last_conversation_at' => now(),
        ]);
    }

}
