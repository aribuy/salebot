<?php
$client = auth()->user()->client;
$activeContactsCount = $client->whatsAppContacts()->count();
$totalContactsCount = $query->contactList->count() ?? 0;
$total_percent = $activeContactsCount > 0 ? ($totalContactsCount / $activeContactsCount) * 100 : 0;
?>
<h5>{{ $totalContactsCount }}</h5>
<p>{{ number_format($total_percent, 0) }} % {{__('of_your_contacts') }}</p>
