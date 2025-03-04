<div>
    @if($feature->type == 'whatsapp')
        {{ __('whatsapp') }}
    @elseIf($feature->type == 'telegram')
        {{ __('telegram') }}
    @endif
</div>