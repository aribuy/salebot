@php
    use App\Helpers\TextHelper;
@endphp
<div class="accordion border-0" id="accordionPreview">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePreview"
                aria-expanded="true" aria-controls="collapsePreview">
                {{ __('preview') }}
            </button>
        </h2>
        <div id="collapsePreview" class="accordion-collapse collapse show" data-bs-parent="#accordionPreview">
            <div class="accordion-body">
                <div class="whatsapp-preview">
                    {{-- <div class="marvel-device nexus5"> --}}
                    <div class="temp-pre">
                        <div class="screen ">
                            <div class="screen-container">
                                <div class="screen-container">
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
                                                                                <embed
                                                                                    src="{{ $header['example']['header_handle'][0] }}"
                                                                                    width="auto" height="auto"
                                                                                    type="application/pdf">
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
                                                                                    <source
                                                                                        src="{{ $header['example']['header_handle'][0] }}"
                                                                                        type="video/mp4">
                                                                                </video>
                                                                            @endif
                                                                        @break

                                                                        @case('AUDIO')
                                                                            @if (isset($header['example']))
                                                                                <audio controls>
                                                                                    {{-- <source src="horse.ogg" type="audio/ogg"> --}}
                                                                                    <source
                                                                                        src="{{ $header['example']['header_handle'][0] }}"
                                                                                        type="audio/mpeg">
                                                                                    Your browser does not support the audio
                                                                                    element.
                                                                                </audio>
                                                                            @endif
                                                                        @break

                                                                        @case('TEXT')
                                                                            <?php
                                                                            $header = preg_replace('/{{(\d+)}}/', '<span id="" class="text-success header_$1">{{header_$1}}</span>', $header['text']);
                                                                            ?>
                                                                            <h6 class="card-title mb-2 vh-text">
                                                                                {!! $header !!}
                                                                            </h6>
                                                                        @break
                                                                    @endswitch
                                                                @endif
                                                            </div>
                                                            <div class="message-body mb-2">
                                                                <div id="_message_body_text">
                                                                    @if (!empty($body) && !empty($body['text']))
                                                                        <?php
                                                                        $_body = preg_replace('/{{(\d+)}}/', '<span class="text-success body_$1">{{body_$1}}</span>', $body['text']);
                                                                        ?>
                                                                        <p class="card-text v-body">
                                                                            {!! TextHelper::transformText($_body) !!}
                                                                            {{-- {!! $_body !!} --}}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="message-footer">
                                                                <div id="_footer_text">
                                                                    @if (!empty($footer) && !empty($footer['text']))
                                                                        <?php
                                                                        $footer = preg_replace('/{{(\d+)}}/', '<span class="text-success footer_$1">{{footer_$1}}</span>', $footer['text']);
                                                                        ?>
                                                                        {!! $footer !!}
                                                                    @else
                                                                        Thank you for choosing us! Contact support
                                                                        for assistance.
                                                                    @endif
                                                                </div>
                                                                <span class="metadata">
                                                                    <span class="time">12:00 AM</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div id="_footer_btn"
                                                            class="text-center card-footer m-0 p-0 bg-white border-top-0">
                                                            @if (isset($buttons))
                                                                @foreach ($buttons as $button)
                                                                    <div class="tmp-btn-list border-top mt-2">
                                                                        @switch($button['type'])
                                                                            @case('URL')
                                                                                <button class="btn btn-template">

                                                                                    {!! $button['text'] !!} </button>
                                                                            @break

                                                                            @case('PHONE_NUMBER')
                                                                                <button class="btn btn-template"><i
                                                                                        class="las la-phone"></i>
                                                                                    {!! $button['text'] !!} </button>
                                                                            @break

                                                                            @case('OTP')
                                                                                <button class="btn btn-template"><i
                                                                                        class="las la-copy"></i>
                                                                                    {!! $button['text'] !!}</button>
                                                                            @break

                                                                            @case('QUICK_REPLY')
                                                                                <button class="btn btn-template"><i
                                                                                        class="las la-reply"></i>
                                                                                    {!! $button['text'] !!}</button>
                                                                            @break
                                                                        @endswitch
                                                                    </div>
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
    </div>
    @if ($variables)
        @foreach ($variables as $key => $variable)
            @if ($variable)
                <h6 class="v-section-title border-bottom pb-2">{{ ucfirst($key) }}</h6>
                <div class="">
                    @switch($key)
                        @case('header')
                            {{-- @case('header', 'body') --}}
                            @foreach ($variable as $_key => $item)
                                <div class="row">
                                    <div class="col-sm-6 mb-3 match-value-select">
                                        <label class="form-label">
                                            {{ __('match_value') }}
                                        </label>
                                        <select name="header_matchs[{{ $item['id'] }}]" class="form-select body-match-select">
                                            <option value="input_value">{{ __('use_input_value') }}</option>
                                            <option value="contact_name">
                                                {{ __('contact_name') }}
                                            </option>
                                            <option value="contact_phone">
                                                {{ __('contact_phone') }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3 body-value-input">
                                        <label class="form-label" for="{{ $item['id'] }}">
                                            {{ __('variable') }} {{ $item['id'] }}
                                        </label>
                                        <input type="text" class="form-control live_preview"
                                            data-target=".header_{{ $item['id'] }}" id="header_{{ $item['id'] }}"
                                            name="header_values[{{ $item['id'] }}]"
                                            placeholder="{{ $item['exampleValue'] }}" value="{{ $item['exampleValue'] }}" />
                                    </div>
                                </div>
                            @endforeach
                        @case('body')
                            @foreach ($variable as $_key => $item)
                                <div class="row">
                                    <div class="col-sm-6 mb-3 match-value-select">
                                        <label class="form-label">
                                            {{ __('match_value') }} {{ $item['id'] }}
                                        </label>
                                        <select name="body_matchs[{{ $item['id'] }}]" class="form-select body-match-select">
                                            <option value="input_value">{{ __('use_input_value') }}</option>
                                            <option value="contact_name">
                                                {{ __('contact_name') }}
                                            </option>
                                            <option value="contact_phone">
                                                {{ __('contact_phone') }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3 body-value-input">
                                        <label class="form-label" for="{{ $item['id'] }}">
                                            {{ __('variable') }} {{ $item['id'] }}
                                        </label>
                                        <input type="text" class="form-control live_preview"
                                            data-target=".body_{{ $item['id'] }}" id="body_{{ $item['id'] }}"
                                            name="body_values[{{ $item['id'] }}]" placeholder="{{ $item['exampleValue'] }}"
                                            value="{{ $item['exampleValue'] }}" />
                                    </div>
                                </div>
                            @endforeach
                        @break

                        @case('document')
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="document" class="form-label">
                                        {{ __('document') }}
                                    </label>
                                    <input type="file" id="pdf" class="form-control boot-file-input"
                                        name="document" placeholder="" value="" accept="application/pdf" required />
                                </div>
                            </div>
                        @break

                        @case('image')
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div id="" class="mb-4">
                                        <label for="image" class="form-label">
                                            {{ __('header_image') }}
                                        </label>
                                        <input type="file" id="header_image"
                                            class="form-control boot-file-input header_file" name="image" placeholder=""
                                            value="" accept="image/*" required />
                                    </div>
                                </div>
                            </div>
                        @break

                        @case('video')
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="video" class="form-label">
                                        {{ __('video') }}
                                    </label>
                                    <input type="file" id="video" class="form-control boot-file-input" name="video"
                                        placeholder="" value="" accept="video/mp4,video/x-m4v,video/*" required />
                                </div>
                            </div>
                        @break

                        @case('buttons')
                            @foreach ($variable as $button)
                                @foreach ($button as $keybtn => $_item)
                                    @switch($_item['type'])
                                        @case('URL')
                                            <div class="row">
                                                <div class="col-6 mb-3 match-value-select">
                                                    <label class="form-label">
                                                        {{ __('match_value') }}
                                                    </label>
                                                    <select name="button_matchs[{{ $_item['id'] }}]"
                                                        class="form-select body-match-select">
                                                        <option value="input_value">{{ __('use_input_value') }}</option>
                                                        <option value="contact_name">
                                                            {{ __('contact_name') }}
                                                        </option>
                                                        <option value="contact_phone">
                                                            {{ __('contact_phone') }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3 body-value-input">
                                                    <label class="form-label" for="{{ $_item['id'] }}">
                                                        {{ __('variable') }}
                                                    </label>
                                                    <input type="text" class="form-control live_preview"
                                                        data-target=".button_{{ $_item['id'] }}" id="button_{{ $_item['id'] }}"
                                                        name="button_values[{{ $_item['id'] }}]"
                                                        placeholder="{{ $_item['exampleValue'] }}"
                                                        value="{{ $_item['exampleValue'] }}" />
                                                </div>
                                            </div>
                                        @break

                                        @case('COPY_CODE')
                                            <div class="row">
                                                <div class="col-6 mb-3 match-value-select">
                                                    <label class="form-label">
                                                        {{ __('match_value') }}
                                                    </label>
                                                    <select name="button_matchs[{{ $_item['id'] }}]"
                                                        class="form-select body-match-select">
                                                        <option value="input_value">{{ __('use_input_value') }}</option>
                                                        <option value="contact_name">
                                                            {{ __('contact_name') }}
                                                        </option>
                                                        <option value="contact_phone">
                                                            {{ __('contact_phone') }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3 body-value-input">
                                                    <label class="form-label" for="{{ $_item['id'] }}">
                                                        {{ __('variable') }}
                                                    </label>
                                                    <input type="text" class="form-control live_preview"
                                                        data-target=".button_{{ $_item['id'] }}" id="button_{{ $_item['id'] }}"
                                                        name="button_values[{{ $_item['id'] }}]"
                                                        placeholder="{{ $_item['exampleValue'] }}"
                                                        value="{{ $_item['exampleValue'] }}" />
                                                </div>
                                            </div>
                                        @break
                                    @endswitch
                                @endforeach
                            @endforeach
                        @break

                    @endswitch
            @endif
        @endforeach
</div>
@endif
