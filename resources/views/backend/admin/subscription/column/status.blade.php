<td class="text-center">
    @if($subscription->status == 0)
        <span class="badge rounded-pill badge subscription-status bg-warning bg-opacity-75" style="line-height: 15px!important;">{{ __('pending')}}</span>
    @elseif($subscription->status == 1)
        <span class="badge rounded-pill badge subscription-status bg-opacity-75" style="line-height: 15px!important; background-color: #25ab7c!important;">{{ __('active')}}</span>
    @elseif($subscription->status == 2)
        <span class="badge rounded-pill badge subscription-status bg-danger bg-opacity-75" style="line-height: 15px!important;">{{ __('rejected')}}</span>
    @elseif($subscription->status == 3)
        <span class="badge rounded-pill badge subscription-status bg-secondary bg-opacity-75" style="line-height: 15px!important;">{{ __('inactive')}}</span>
    @endif
</td>