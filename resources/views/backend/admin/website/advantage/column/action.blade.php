<ul class="d-flex gap-30 justify-content-end">
	@can('advantage.edit')
		<li>
			<a href="{{ route('advantage.edit',$advantage->id) }}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('advantage.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('advantage.destroy', $advantage->id) }}')"
			   data-toggle="tooltip"
			   data-original-title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
</ul>
