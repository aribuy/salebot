<ul class="d-flex gap-1 justify-content-end">
	<div class="d-flex align-items-center gap-4">
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('client.list.delete', $query->id) }}')"
			   data-bs-toggle="tooltip"
			   title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
		<li>
			<a href="{{ route('client.list.edit', $query->id) }}" title={{__("edit")}}>
				<i class="las la-edit"></i>
			</a>
		</li>
		<div>
			<a href="javascript:void(0)" data-id="{{ $query->id }}" data-type="" class="__add_contact d-flex align-items-center btn sg-btn-primary gap-2"  title="{{ __('add_new_contacts') }}">
				<i class="las la-user-plus"></i>
			</a>
		</div>
	</div>
</ul>
