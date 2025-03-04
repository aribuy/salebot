<ul class="d-flex gap-30 justify-content-end">
    <li>
        <a href="{{ route('client.chat.index',['contact'=>@$query->contact->id]) }}" data-id="{{ $query->id }}" class="__template_modal"  title="{{ __('chat') }}"><i class="las la-comment"></i></a>
    </li>

</ul>