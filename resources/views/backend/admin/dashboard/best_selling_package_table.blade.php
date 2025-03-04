<table class="table table-borderless best-selling-courses recent-transactions">
    <thead>
        <tr>
            <th>{{__('plan')}}</th>
            <th>{{__('price')}}</th>
            <th>{{__('subscriptions')}}</th>
        </tr>
    </thead>

    <tbody>
    @foreach ($packages as $package)
    <tr>
        <td>{{ $package->name }}</td>
        <td>{{ get_price($package->price) }}</td>
        <td>{{ReadableNumbers::make($package->subscriptions_count,2)}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
