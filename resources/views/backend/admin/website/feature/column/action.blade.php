<ul class="d-flex gap-30 justify-content-end">
	@can('feature.edit')
		<li>
			<a href="{{ route('feature.edit',$feature->id) }}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('feature.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('feature.destroy', $feature->id) }}')"
			   data-toggle="tooltip"
			   data-original-title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
</ul>
