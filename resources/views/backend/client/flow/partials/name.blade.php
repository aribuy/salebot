<div class="">
    <div>
        {{ $flow->name }}
    </div>
    <div>
        @php
            $starterBoxNode = $flow->nodes->where('type', 'starter-box')->first();
            $nodes = $starterBoxNode ? $starterBoxNode->data : null;
        @endphp
        @if($nodes)
            <span class="d-block">{{ __('keyword') }}: {{ $nodes['keyword'] }}</span>
            <span class="d-block">{{ __('matching_type') }}: {{ $nodes['matching_types'] }}</span>
        @else
            <span class="d-block">{{ __('keyword') }}: {{ __('Not available') }}</span>
            <span class="d-block">{{ __('matching_type') }}: {{ __('Not available') }}</span>
        @endif
    </div>
</div>
