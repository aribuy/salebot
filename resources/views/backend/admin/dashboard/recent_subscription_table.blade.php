<table class="table table-borderless best-selling-courses">
    <thead>
        <tr>
            <th>{{__('client')}}</th>
            <th>{{__('price')}}</th>
            <th>{{__('date')}}</th>
        </tr>
    </thead>

    <tbody>
    @foreach($subscriptions as $subscription)
    <tr>
        <td>
            <div class="instructors-pro d-flex align-items-center">
                <div class="inst-avtar">
                    <img src="{{ getFileLink('80x80', @$subscription->client->logo) }}" alt="{{@$subscription->client->company_name}}">
                </div>
                <div class="inst-intro">
                    <h6>{{ @$subscription->client->company_name }}</h6>
                    <p>{{ @$subscription->client->name }}</p>
                </div>
            </div>
        </td>
        <td>{{ get_price(@$subscription->plan->price) }}</td>
        <td>{{ Carbon\Carbon::parse(@$subscription->purchase_date)->format('m-d-Y H:i a') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
