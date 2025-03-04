
<?php
$client = Auth::user()->client;
$activeContactsCount = $client->contacts->count();

$totalContactsCount = $contacts_list->contacts->count() ?? 0;

if ($activeContactsCount > 0) {
    $total_percent = ($totalContactsCount /  $activeContactsCount) * 100;
} else {
    $total_percent = 0;
}
?>
<h5> {{ $totalContactsCount }}</h5>
<p>{{ number_format($total_percent,0) }} % {{__('of_your_contacts') }}</p>

