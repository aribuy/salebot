<?php
$status = $query->status ?? StatusEnum::ACTIVE;
?>
@if ($status == \App\Enums\StatusEnum::ACTIVE)
    <span class="badge rounded-pill bg-success text-capitalize bg-opacity-75"
        style="line-height: 15px!important;">{{ __('active') }}</span>
@elseif ($status == \App\Enums\StatusEnum::HOLD)
    <span class="badge rounded-pill bg-warning text-capitalize bg-opacity-75"
        style="line-height: 15px!important;">{{ __('pause') }}</span>
    @if ($query->errors)
        <span class="text-danger d-block">{{ $query->errors }}</span>
    @endif
@elseif ($status == \App\Enums\StatusEnum::CANCELED)
    <span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75"
        style="line-height: 15px!important;">{{ __('canceled') }}</span>
    @if ($query->errors)
        <span class="text-danger d-block">{{ $query->errors }}</span>
    @endif
@elseif ($status == \App\Enums\StatusEnum::STOPPED)
    <span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75"
        style="line-height: 15px!important;">{{ __('stopped') }}</span>
    @if ($query->errors)
        <span class="text-danger d-block">{{ $query->errors }}</span>
    @endif
@elseif ($status == \App\Enums\StatusEnum::EXECUTED)
    <span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75"
        style="line-height: 15px!important;">{{ __('executed') }}</span>
@else
    <span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75"
        style="line-height: 15px!important;">{{ $status }}</span>
@endif
