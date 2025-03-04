<ul class="d-flex gap-30 justify-content-center ">
    <li><a href="{{ route('client.bot-reply.edit', $reply->id) }}"><i class="las la-edit" title="{{__('edit')}}"></i></a></li>

    <li><a onclick="delete_row('{{ route('client.bot-reply.destroy', $reply->id) }}')"
            href="javascript:void(0)"><i class="las la-trash-alt" title="{{__('delete')}}"></i></a></li>
</ul>
