<ul class="d-flex gap-30 justify-content-end">
	@can('testimonials.edit')
		<li>
			<a href="{{ route('testimonials.edit',$testimonial->id) }}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('testimonials.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('testimonials.destroy', $testimonial->id) }}')"
			   data-toggle="tooltip"
			   data-original-title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
</ul>
