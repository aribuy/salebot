@php
    if($message->is_campaign_msg)
        $class = 'single-sp-card-box';
    elseif($message->header_image)
        $class = 'single-sp-img-box';
    elseif($message->header_video)
        $class = 'single-sp-card-box';
    elseif($message->header_audio)
        $class = 'single-sp-audio-box';
    elseif($message->header_document)
        $class = 'single-sp-card-box';
    else
        $class = 'single-sp-text-area';
@endphp

<div class="single-sp-chat-area {{ $class }}  mt--8 {{ $message->is_contact_msg ? '' : 'text-end' }}">
    @if($message->is_campaign_msg)
        <div class="single-sp-card">
            <div class="sp-card-img {{ $message->header_video ? 'has-vedio-icon' : '' }}">
                @if($message->header_image)
                    <img src="{{ $message->header_image }}" alt="Card Image">
                @endif
                @if($message->header_video)
                    <a href="{{ $message->header_video }}" target="_blank" class="vedio-player-btn popup-video">
                        <i class="las la-play-circle"></i>
                    </a>
                @elseif($message->header_audio)
                    <a href="{{ $message->header_audio }}" class="vedio-player-btn popup-video">
                        <i class="las la-play-circle"></i>
                    </a>
                @elseif($message->header_document)
                    <a href="{{ $message->header_document }}" class="vedio-player-btn popup-video">
                        <i class="las la-play-circle"></i>
                    </a>
                @endif
            </div>
            <div class="card-content">
                @if($message->header_text)
                    <h6 class="title">{{ $message->header_text }}</h6>
                @endif
                <p class="desc">{{ $message->value }} </p>
                @if($message->footer_text)
                    <p class="bottom-text">{{ $message->footer_text }}</p>
                @endif
                @foreach($buttons as $button)
                    @if($button['type'] == 'a')
                        <a href="{{ $button['value'] }}" target="_blank"
                           class="card-btn card-btn-border">{{ $button['text'] }}</a>
                    @else
                        <button type="button" class="card-btn card-btn-border">{{ $button['text'] }}</button>
                    @endif
                @endforeach
            </div>
        </div>
    @elseif($message->header_audio)
        <audio controls crossorigin>
            <source src="{{ $message->header_audio }}" type="audio/mp3">
        </audio>
    @elseif($message->header_image)
        <img src="{{ $message->header_image }}" alt="">
    @elseif($message->header_video)
        <video width="320" height="240" controls>
            <source src="{{ $message->header_video }}" type="video/mp4">
        </video>
    @else
        <div class="chat-box {{ $message->is_contact_msg ? '' : 'bg-primary' }}">{{ $message->value }}</div>
    @endif
    <span class="chat-time-text {{ $message->is_contact_msg ? 'ml--10' : 'mr--10' }}">{{ Carbon\Carbon::parse($message->created_at)->format('H:i A') }}</span>
</div>

