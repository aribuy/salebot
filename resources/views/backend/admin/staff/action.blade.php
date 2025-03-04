<ul class="d-flex gap-30 justify-content-end align-items-center">
	@if(hasPermission('staffs.edit'))
		<li><a href="{{ route('staffs.edit', $staff->id) }}"  title="{{ __('edit') }}"><i class="las la-edit" title="{{__('edit')}}"></i></a></li>
	@endif
	@if(hasPermission('staffs.delete'))
		@if($staff->is_deleted == 0)
			<li><a onclick="delete_row('{{ route('staffs.delete', $staff->id) }}')"
			       href="javascript:void(0)"><i class="las la-trash-alt" title="{{__('delete')}}"></i></a></li>
		@else
			<li><a onclick="delete_row('{{ route('staffs.delete', $staff->id) }}')"
			       href="javascript:void(0)"  title="{{ __('delete') }}"><i class="las la-redo-alt"></i></a></li>
		@endif
	@endif
	@can('user.ban')
		<div class="dropdown">
			<a class="dropdown-toggle" href="#" role="button"
			   data-bs-toggle="dropdown" aria-expanded="false">
				<i class="las la-ellipsis-v"></i>
			</a>
			<ul class="dropdown-menu">
				@if($staff->is_user_banned == 0)
					<li><a class="dropdown-item"
					       href="{{ route('staffs.bannUser', $staff->id) }}" title="{{ __('ban_this_person') }}">{{__('ban_this_person')}}</a></li>
				@else
					<li><a class="dropdown-item"
					       href="{{ route('staffs.bannUser', $staff->id) }}" title="{{ __('active_this_person') }}">{{__('active_this_person')}}</a></li>
				@endif
			</ul>
		</div>
	@endcan
</ul>
