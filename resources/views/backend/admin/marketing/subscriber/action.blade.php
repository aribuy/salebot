<ul class="d-flex gap-30 justify-content-center">
    @if(hasPermission('subscribers.destroy'))
        <li>
            <a href="javascript:void(0)"
               onclick="delete_row('{{ route('subscribers.destroy', $query->id) }}')"
               data-toggle="tooltip"
               data-original-title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
        </li>
    @endif
</ul>