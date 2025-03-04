@php
    use App\Helpers\TextHelper;
@endphp
<div class="whatsapp-preview">
    <div class="marvel-device nexus5">
        <div class="top-bar"></div>
        <div class="sleep"></div>
        <div class="volume"></div>
        <div class="camera"></div>
        <div class="screen">
            <div class="screen-container">
                <div class="screen-container">
                    <div class="status-bar">
                        <div class="time"></div>
                        <div class="battery">
                            <i class="la la-battery"></i>
                        </div>
                        <div class="network">
                            <i class="la la-network-wired"></i>
                        </div>
                        <div class="wifi">
                            <i class="la la-wifi"></i>
                        </div>
                        <div class="star">
                            <i class="la la-star"></i>
                        </div>
                    </div>
                    <div class="chat">
                        <div class="chat-container">
                            <div class="conversation">
                                <div class="conversation-container">
                                    <div class="message received card">
                                        <div class="card-body m-0 p-0">
                                            <div id="message-header" class="message-header py-2">
                                                @if (!empty($header))
                                                    @switch($header['format'])
                                                        @case('DOCUMENT')
                                                            @if (isset($header['example']))
                                                                <embed src="{{ $header['example']['header_handle'][0] }}"
                                                                    width="auto" height="auto" type="application/pdf">
                                                            @endif
                                                        @break

                                                        @case('IMAGE')
                                                            @if (isset($header['example']))
                                                                <img src="{{ $header['example']['header_handle'][0] }}"
                                                                    alt="Header image" style="">
                                                            @endif
                                                        @break

                                                        @case('VIDEO')
                                                            @if (isset($header['example']))
                                                                <video width="100%" height="200" controls>
                                                                    <source src="{{ $header['example']['header_handle'][0] }}"
                                                                        type="video/mp4">
                                                                </video>
                                                            @endif
                                                        @break

                                                        @case('AUDIO')
                                                            @if (isset($header['example']))
                                                                <audio controls>
                                                                    <source src="{{ $header['example']['header_handle'][0] }}"
                                                                        type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            @endif
                                                        @break

                                                        @case('TEXT')
                                                            <?php
                                                            // $header = preg_replace('/{{(\d+)}}/', '<span id="" class="text-success header_$1">{{header_$1}}</span>', $header['text']);
                                                            ?>
                                                            {{-- {!! $header !!} --}}
                                                            {!! nl2br(e($header['text'])) !!}
                                                        @break
                                                    @endswitch
                                                @else
                                                    ⭐ Limited Time Offer! Enjoy Exclusive Discounts ⭐
                                                @endif
                                            </div>
                                            <div class="message-body mb-2">
                                                <div id="_message_body_text">
                                                    @if (!empty($body) && !empty($body['text']))
                                                        <?php
                                                        // $_body = preg_replace('/{{(\d+)}}/', '<span class="text-success body_$1">{{body_$1}}</span>', $body['text']);
                                                        ?>
                                                        {{-- {!! nl2br(e($body['text'])) !!} --}}
                                                        {!! TextHelper::transformText($body['text']) !!}
                                                    @else
                                                        🔥 🍂 Get the best deals on our products! Shop now and save with
                                                        our
                                                        latest promotions.
                                                        Visit our website or use the buttons below to learn more.
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="message-footer">
                                                <div id="_footer_text">
                                                    @if (!empty($footer) && !empty($footer['text']))
                                                        {!! nl2br(e($footer['text'])) !!}
                                                    @else
                                                        Thank you for choosing us! Contact support for assistance.
                                                    @endif
                                                </div>
                                                <span class="metadata">
                                                    <span class="time">12:00 AM</span>
                                                </span>
                                            </div>
                                            <br>
                                            <div id="_footer_btn" class="text-center">
                                                <div class="tmp-btn-list text-center mt-2 border-top-0">

                                                    @if (isset($buttons)) 
                                                        @foreach ($buttons as $key => $button)
                                                            @switch($button['type'])

                                                                @case('URL')
                                                                    <button data-action="visit_website" data-max="2"
                                                                        id="{{ $key }}_preview"
                                                                        class="btn btn-template w-100 border-top rounded-0 border-radius-0"><i
                                                                            class="las la-external-link-alt"></i>
                                                                        {{ $button['text'] }} </button>
                                                                @break

                                                                @case('PHONE_NUMBER')
                                                                    <button data-action="call_phone_number" data-max="1"
                                                                        id="{{ $key }}_preview"
                                                                        class="btn btn-template w-100 border-top rounded-0 border-radius-0"><i
                                                                            class="las la-phone"></i>
                                                                        {{ $button['text'] }} </button>
                                                                @break

                                                                @case('OTP')
                                                                    <button data-action="${actionType}" data-max="1"
                                                                        id="{{ $key }}_preview"
                                                                        class="btn btn-template w-100 border-top rounded-0 border-radius-0"><i
                                                                            class="las la-copy"></i>
                                                                        {{ $button['text'] }}</button>
                                                                @break

                                                                @case('COPY_CODE')
                                                                    <button data-action="copy_offer_code" data-max="1"
                                                                        id="{{ $key }}_preview"
                                                                        class="btn btn-template w-100 border-top rounded-0 border-radius-0"><i
                                                                            class="las la-copy"></i>
                                                                        {{ $button['text'] }}</button>
                                                                @break

                                                                @case('QUICK_REPLY')
                                                                    <button data-action="quick_reply" data-max="10"
                                                                        id="{{ $key }}_preview"
                                                                        class="btn btn-template w-100 border-top rounded-0 border-radius-0"><i
                                                                            class="las la-reply"></i>
                                                                        {{ $button['text'] }}</button>
                                                                @break
                                                            @endswitch
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
