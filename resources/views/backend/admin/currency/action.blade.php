<ul class="d-flex gap-30 justify-content-end align-items-center">
	@can('currencies.edit')
		<li>
			<a class="edit_modal" href="javascript:void(0)"
			   data-fetch_url="{{ route('currencies.edit',$currency->id) }}"
			   data-route="{{ route('currencies.update',$currency->id) }}" data-modal="currency" title="{{__('edit')}}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('currencies.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('currencies.destroy', $currency->id) }}')"
			   data-toggle="tooltip"
			   title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
	@can('currencies.default-currency')
		<div class="dropdown">
			<a class="dropdown-toggle" href="#" role="button"
			   data-bs-toggle="dropdown" aria-expanded="false">
				<i class="las la-ellipsis-v"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item"
				       href="{{ route('currencies.default-currency', $currency->id) }}"  title="{{ __('set_as_default') }}">{{__('set_as_default') }}</a>
				</li>
			</ul>
		</div>
	@endcan
</ul>
