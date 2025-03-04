<ul class="d-flex gap-30 justify-content-center ">
    @if(hasPermission('roles.edit'))
        <li><a href="{{ route('roles.edit', $role->id) }}" title="{{__('edit')}}"><i class="las la-edit"></i></a></li>
    @endif
    @if(hasPermission('roles.destroy'))
        <li><a onclick="delete_row('{{ route('roles.destroy', $role->id) }}')"
               href="javascript:void(0)" title="{{__('delete')}}"><i class="las la-trash-alt"></i></a></li>
    @endif
</ul>
