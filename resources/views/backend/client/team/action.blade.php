<ul class="d-flex gap-30 justify-content-end align-items-center">
	@if (!(auth()->user()->id == $user->id))
		@if(!$user->is_primary)
			<li>
				<a class="edit_modal" href="{{ route('client.team.edit',$user->id) }}"
				   title="{{ __('edit') }}"><i class="las la-edit"></i></a>
			</li>
		@endif
	@endif
	@if (!(auth()->user()->id == $user->id))
		@if (!$user->is_primary)
			<div class="dropdown">
				<a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="las la-ellipsis-v"></i>
				</a>
				<ul class="dropdown-menu">
					@if ($user->is_user_banned == 0)
						<li><a class="dropdown-item"
						       href="{{ route('users.ban', $user->id) }}" title="{{ __('ban_this_staff') }}">{{ __('ban_this_staff') }}</a>
						</li>
					@else
						<li><a class="dropdown-item"
						       href="{{ route('users.ban', $user->id) }}" title="{{ __('active_this_staff') }}">{{ __('active_this_staff') }}</a>
						</li>
					@endif
				</ul>
			</div>
		@endif
	@endif
</ul>
