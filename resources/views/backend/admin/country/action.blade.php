<ul class="d-flex gap-30 justify-content-end align-items-center">
	@can('countries.edit')
		<li>
			<a class="edit_modal" href="javascript:void(0)"
			   data-fetch_url="{{ route('countries.edit',$country->id) }}"
			   data-route="{{ route('countries.update',$country->id) }}" data-modal="country" title="{{__('edit')}}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('countries.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('countries.destroy', $country->id) }}')"
			   data-toggle="tooltip"
			   data-original-title="{{ __('delete') }}" title="{{__('delete')}}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
</ul>
