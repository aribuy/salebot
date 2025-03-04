<?php 
$client = auth()->user()->client;
// Get total message count and count of read messages in a single query
$messageCounts = DB::table('messages')
    ->where('contacts.client_id', $client->id)
    ->leftJoin('contacts', 'contacts.id', '=', 'messages.contact_id')
    ->leftJoin('contact_relation_lists', 'contacts.id', '=', 'contact_relation_lists.contact_id')
    ->where('contact_relation_lists.contact_list_id', $query->id)
    ->where('is_contact_msg', 0)
    ->groupBy('contact_relation_lists.contact_id')
    ->selectRaw('COUNT(*) AS total_messages, SUM(CASE WHEN messages.status = "read" THEN 1 ELSE 0 END) AS read_messages')
    ->first();

$total_message = $messageCounts->total_messages ?? 0;
$total_message_read = $messageCounts->read_messages ?? 0;

// Calculate the percentage of read messages
$readPercentage = ($total_message > 0) ? ($total_message_read / $total_message) * 100 : 0;

// Output the results
?>
<h5>{{ number_format($readPercentage, 0) }}%</h5>
<p>{{ $total_message_read }} {{__('of_the')}} {{ $total_message }} {{__('contacts_messaged')}}</p>
