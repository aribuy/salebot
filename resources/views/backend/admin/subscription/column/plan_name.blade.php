<div class="user-info-panel d-flex gap-12 align-items-center">
    <div class="user-img">
        <img src="{{ getFileLink('80x80', @$subscription->client->logo) }}" alt="{{ @$subscription->client->company_name }}">
    </div>
    <div class="user-info">
        <h4>{{ @$subscription->plan->name }}</h4>
        <span>{{__('subscribed_by')}} {{ @$subscription->client->company_name }}</span>
    </div>
</div>

