<div class="d-flex align-items-center gap-2">
    <div>
        <a href="{{ route('client.telegram.campaigns.view',$query->id) }}?campaign_id={{ $query->id }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
            <i class="las la-chart-bar"></i>
            <span>{{__('statistics')}}</span>
        </a>
    </div>
	<div class="dropdown">
		<a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
			<i class="las la-ellipsis-v"></i>
		</a>
		<ul class="dropdown-menu">
			@if ($query->status==\App\Enums\StatusEnum::ACTIVE)
			<li><a class="dropdown-item __js_update_status" data-id="{{ $query->id }}" data-status="{{ \App\Enums\StatusEnum::HOLD->value }}" href="javascript:void(0);" data-url="{{ route('client.whatsapp.campaigns.status.update',$query->id) }}" title="{{ __('pause') }}">{{ __('pause') }}</a></li>
			<li><a class="dropdown-item __js_update_status" data-id="{{ $query->id }}" data-status="{{ \App\Enums\StatusEnum::CANCELED->value }}" href="javascript:void(0);" data-url="{{ route('client.whatsapp.campaigns.status.update',$query->id) }}" title="{{ __('cancel') }}">{{ __('cancel') }}</a></li>
			@endif
			@if ($query->status==\App\Enums\StatusEnum::HOLD || $query->status==\App\Enums\StatusEnum::CANCELED)	
			<li><a class="dropdown-item __js_update_status" data-id="{{ $query->id }}" data-status="{{ \App\Enums\StatusEnum::ACTIVE->value }}" href="javascript:void(0);" data-url="{{ route('client.whatsapp.campaigns.status.update',$query->id) }}" title="{{ __('active') }}">{{ __('active') }}</a></li>
			@endif
		</ul>
	</div>
</div>
