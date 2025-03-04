<ul class="d-flex gap-30 justify-content-end">
        <li>
            <a href="{{ route('client.segment.edit',$segments->id) }}"  title="{{ __('edit') }}"><i class="las la-edit" title="{{__('edit')}}"></i></a>
        </li>
    <li>
        <a href="javascript:void(0)"
           onclick="delete_row('{{ route('client.segment.delete', $segments->id) }}')"
           data-bs-toggle="tooltip"
           title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
    </li>
</ul>