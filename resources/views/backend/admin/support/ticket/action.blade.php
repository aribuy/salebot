<ul class="d-flex gap-30 justify-content-end">
    @if(hasPermission('tickets.edit'))
        <li>
            <a href="{{ route('tickets.show',$ticket->id) }}" title="{{__('open')}}"><i
                    class="lar la-eye"></i></a>
        </li>
    @endif
</ul>
