<div>
    <span class="d-block">
        {{ @$client->activeSubscription->plan->name ?? __('no_active_subscription') }}
    </span>
    @if ( @$client->activeSubscription->purchase_date)
    <span class="d-block">
        {{ __('purchase_date') }}:
        {{ @$client->activeSubscription->purchase_date ? @\Carbon\Carbon::parse($client->activeSubscription->purchase_date)->format('Y-m-d') : 'N/A' }}
    </span>  
    @endif
    @if (@$client->activeSubscription)
    <span class="d-block">
        {{ __('expiration_date') }}:
        {{ @$client->activeSubscription->expire_date ? @\Carbon\Carbon::parse($client->activeSubscription->expire_date)->format('Y-m-d') : 'N/A' }}
    </span>
    @endif
</div>
