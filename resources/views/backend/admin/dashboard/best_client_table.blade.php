<table class="table table-borderless best-selling-courses best-instructor">
    <thead>
        <tr>
            <th>{{__('name')}}</th>
            <th>{{__('total_spend')}}</th>
            <th>{{__('started_at')}}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($best_client as $client)
            <tr>
                <td>
                    <div class="instructors-pro d-flex align-items-center">
                        <div class="inst-avtar">
                            <img src="{{ getFileLink('80x80', $client->logo) }}" alt="{{ $client->company_name }}">
                        </div>
                        <div class="inst-intro">
                            <h6>{{ $client->company_name }}</h6>
                            <p>{{ $client->name }}</p>
                        </div>
                    </div>
                </td>
                <td>{{ get_price($client->subscriptions_sum_price) }}</td>
                <td>{{ Carbon\Carbon::parse($client->created_at)->format('d M Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
