<ul class="d-flex gap-30 justify-content-end">
	<li>
		<a class="__js_sync" href="{{ route('client.template.edit',$row->id) }}"
			data-id="{{ @$row->id }}"
			title="{{ __('edit') }}"><i class="las la-edit"></i></a>
	</li>
	<li>
		<a href="{{ route('client.template.sync', @$row->id) }}" data-id="{{ @$row->id }}" title={{__("check_status")}} class="__js_sync" >
			<i class="las la-sync-alt"></i>
		</a>
	</li>
    <a href="javascript:void(0);" data-id="{{ $row->id }}"
        data-url="{{ route('client.template.delete', @$row->id) }}" class="text-danger __js_delete"
        data-bs-toggle="tooltip" aria-label="{{__('delete')}}" data-bs-original-title="{{__('delete')}}">
        <i class="las la-trash-alt"></i>
    </a>
</ul>