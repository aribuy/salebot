@if(isset($query) && isset($query->messages))
	<p>{{__('total_sent')}}: {{ $query->messages->whereIn('status',[\App\Enums\MessageStatusEnum::SENT,\App\Enums\MessageStatusEnum::READ, \App\Enums\MessageStatusEnum::DELIVERED])->count()  }}</p>
@else
	<p>{{__('total_sent')}}: 0</p>
@endif

@if(isset($query) && isset($query->messages))
	<p>{{__('total_delivered')}}: {{ $query->messages->whereIn('status', [\App\Enums\MessageStatusEnum::READ, \App\Enums\MessageStatusEnum::DELIVERED])->count() }}</p>
@else
	<p>{{__('total_delivered')}}: 0</p>
@endif

@if(isset($query) && isset($query->messages))
	<p>{{__('total_read')}}: {{ $query->messages->where('status',\App\Enums\MessageStatusEnum::READ)->count() }}</p>
@else
	<p>{{__('total_read')}}: 0</p>
@endif

@if(isset($query) && isset($query->messages))
	<p>{{__('total_failed')}}: {{ $query->messages->where('status',\App\Enums\MessageStatusEnum::FAILED)->count() }}</p>
@else
	<p>{{__('total_failed')}}: 0</p>
@endif


