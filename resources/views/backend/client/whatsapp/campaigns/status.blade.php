<td class="text-capitalize">
	@if ($message->status == \App\Enums\MessageStatusEnum::SCHEDULED)
		<span class="badge rounded-pill bg-success text-capitalize bg-opacity-75" style="line-height: 15px!important;">
            {{ $message->status }}
        </span>
	@elseif ($message->status == \App\Enums\MessageStatusEnum::DELIVERED)
		<span class="badge rounded-pill bg-info text-capitalize bg-opacity-75" style="line-height: 15px!important;">
            {{ $message->status }}
        </span>
	@elseif ($message->status == \App\Enums\MessageStatusEnum::READ)
		<span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
            {{ $message->status }}
        </span>
	@elseif ($message->status == \App\Enums\MessageStatusEnum::FAILED)
		<span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75" style="line-height: 15px!important;">
            {{ $message->status }}
        </span>
		<span class="text-danger d-block">
            {{ $message->error }}
        </span>
	@else
		<span class="badge rounded-pill bg-secondary text-capitalize bg-opacity-75"
		      style="line-height: 15px!important;">
            {{ $message->status }}
        </span>
	@endif
</td>
