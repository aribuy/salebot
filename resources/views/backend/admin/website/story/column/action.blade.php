<ul class="d-flex gap-30 justify-content-end">
	@can('story_section.edit')
		<li>
			<a href="{{ route('story.edit',$story->id) }}"><i
						class="las la-edit"></i></a>
		</li>
	@endcan
	@can('story_section.destroy')
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('story.destroy', $story->id) }}')"
			   data-toggle="tooltip"
			   data-original-title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endcan
</ul>
