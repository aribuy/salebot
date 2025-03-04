<ul class="d-flex gap-30 justify-content-center align-items-center">
    <li>
        <a class="edit_modal" href="{{ route('client.flow-builders.show',$flow->id) }}"
           title="{{ __('edit') }}"><i class="las la-edit"></i></a>
    </li>
    <li>
        <a href="javascript:void(0)" onclick="delete_row('{{ route('client.flow-builders.destroy', $flow->id) }}')"
           data-toggle="tooltip" data-original-title="{{ __('delete') }}">
            <i class="las la-trash-alt"></i>
        </a>
    </li>
</ul>
