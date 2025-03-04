<ul class="d-flex gap-30 justify-content-end">
	@can('faqs.edit')
		<li>
			<a href="{{ route('faqs.edit',$faq->id) }}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('faqs.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('faqs.destroy', $faq->id) }}')"
			   data-toggle="tooltip"
			   data-original-title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
</ul>

