@can('subscription.edit',)
	<ul class="d-flex gap-1">
		<div style="width: 80px!important">
			@if($subscription->status == 0)
				<a href="javascript:void(0)"
				   class="subscription-button btn-sm d-flex align-items-center justify-content-center gap-1 btn btn-success text-light"
				   onclick="delete_row('{{ route('subscribe-list.status', ['id' => $subscription->id,'status' => 1]) }}')"
				   data-toggle="tooltip"><span class="text-center">@if($subscription->payment_method == 'offline'){{__('approve')}}@else{{ __('active') }}@endif</span>
				</a>
			@elseif($subscription->status == 1)
				<a href="javascript:void(0)"
				   onclick="delete_row('{{ route('subscribe-list.status', ['id' => $subscription->id,'status' => 3]) }}')"
				   data-toggle="tooltip"
				   class="subscription-button btn-sm d-flex align-items-center justify-content-center gap-1 btn btn-secondary text-light"
				><span class="text-center">{{ __('inactive') }}</span>
				</a>
			@elseif($subscription->status == 2 || $subscription->status == 3)
				<a href="javascript:void(0)"
				   onclick="delete_row('{{ route('subscribe-list.status', ['id' => $subscription->id,'status' => 1]) }}')"
				   data-toggle="tooltip"
				   class="subscription-button btn-sm d-flex align-items-center justify-content-center gap-1 btn btn-success text-light"
				><span class="text-center">{{ __('active') }}</span>
				</a>
			@endif
		</div>
		<div class="dropdown">
			<a class="dropdown-toggle btn-sm" href="#" role="button" data-bs-toggle="dropdown"
			   style="height: 32px!important;">
				<i class="las la-ellipsis-v"></i>
			</a>
			<ul class="dropdown-menu">
				@if($subscription->status == 1 && $subscription->is_recurring == 1)
					<li>
						<a class="dropdown-item" href="javascript:void(0)"
						   onclick="delete_row('{{ route('stop.recurring', $subscription->id) }}')"
						   data-toggle="tooltip" data-status="4"
						   data-subscription-id="{{ $subscription->id }}">{{ __('stop_recurring') }}</a>
					</li>
				@endif
				@if($subscription->payment_method == 'offline' && $subscription->status == 0)
					<li>
							<a class="dropdown-item subscription-button" href="javascript:void(0)"
							   onclick="delete_row('{{ route('subscribe-list.status', ['id' => $subscription->id,'status' => 2]) }}')"
							   data-toggle="tooltip">{{ __('reject') }}</a>
					</li>
				@endif
				<li>
					<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#credit"
					   data-subscription-id="{{ $subscription->id }}">{{ __('add_extra_credit') }}</a>
				</li>
			</ul>
		</div>
	</ul>
@endcan