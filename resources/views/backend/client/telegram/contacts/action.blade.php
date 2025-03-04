<ul class="d-flex gap-30 justify-content-end">
    <li>
        <a data-url="{{ route('client.telegram.subscriber.sync', @$contacts->id) }}" id="__sync_subscriber" data-id="{{ $contacts->id }}" title={{__("sync_subscriber")}} class="__js_update" >
			<i class="las la-sync-alt"></i>
		</a>
    </li>
    <li>
        @if($contacts->is_blacklist == 0)
            <a href="javascript:void(0);" data-url="{{ route('client.telegram.subscriber.add-blacklist', $contacts->id) }}" data-id="{{ $contacts->id }}" title="{{__('add-blacklist')}}" class="__js_update">
                    <i class="las la-ban"></i>
            </a>
        @else
            <a href="javascript:void(0);" data-id="{{ $contacts->id }}" data-url="{{ route('client.telegram.subscriber.remove-blacklist', $contacts->id) }}" title="{{__("remove-blacklist")}}" class="__js_update">
                <i class="las la-check-circle"></i>
            </a>
        @endif
    </li>
    <li>
        <a data-url="{{ route('client.telegram.subscriber.delete', $contacts->id) }}" data-id="{{ $contacts->id }}"
           href="javascript:void(0);" title="{{__('delete')}}" class="__js_update">
           <i class="las la-trash-alt"></i>
        </a>
    </li>
</ul>
