<p>{{__('price')}}: {{get_price($subscription->price)}}</p>
@if($remainingPeriod)
    <p>{{ $remainingPeriod }}</p>
@endif