<ul class="d-flex gap-30 justify-content-end align-items-center">
	@can('client.edit')
		<li>
			<a href="{{ route('clients.edit', @$client->id) }}"
			   data-bs-toggle="tooltip"
			   title="{{ __('edit') }}"><i class="las la-edit"></i></a>
		</li>
	@endcan
	@if(hasPermission('client.destroy'))
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('team.delete', @$client->id) }}')"
			   data-bs-toggle="tooltip"
			   title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endif
	@if(hasPermission('client.log_in'))
		<div class="dropdown">
			<a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
			   title="{{ __('login') }}">
				<i class="las la-sign-in-alt"></i>
			</a>
			<ul class="dropdown-menu">
				@foreach(@$client->staff as $staff)
					<li><a class="dropdown-item"
					       href="{{ route('login.as', @$staff->user->id) }}"  title="{{ __('login_as') }}">{{ __('as') }}  {{ @$staff->user->name}}</a>
					</li>
				@endforeach
			</ul>
		</div>
	@endif
</ul>



