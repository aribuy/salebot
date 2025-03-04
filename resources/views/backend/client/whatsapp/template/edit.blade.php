@extends('backend.layouts.master')
@section('title', __('templates'))
@section('content')
    @push('css_asset')
        <link rel="stylesheet" href="{{ static_asset('admin/css/devices.min.css') }}">
        <link rel="stylesheet" href="{{ static_asset('admin/css/template.css') }}">
    @endpush
    <section class="oftions">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col col-lg-12 col-md-12">
                    <div class="d-flex align-items-center justify-content-between mb-12">
                        <h3 class="section-title">{{ __('edit_whatsapp_template') }}</h3>
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <a href="{{ route('client.templates.index') }}"
                                    class="d-flex align-items-center btn sg-btn-primary gap-2">
                                    <i class="las la-list-alt"></i>
                                    <span>{{ __('template_lists') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row">
                            <div class="col-lg-8">
                                <form method="POST" action="{{ route('client.template.update', $row->id) }}"
                                    id="whatsapp-template-update" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="template_name">{{ __('template_name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="template_name"
                                                    name="template_name" placeholder="{{ __('enter_template_name') }}"
                                                    value="{{ old('template_name', $row->name) }}" maxlength="512" required>
                                                <div class="invalid-feedback text-danger"></div>
                                                <small id="nameCharCount"
                                                    class="text-muted text-end">{{ __('characters') }}: 0 /
                                                    512</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="locale">{{ __('locale') }}
                                                    <span class="text-danger">*</span></label>
                                                <select class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                    id="locale" name="locale" required>
                                                    @foreach (config('static_array.whatsapp_supported_languages') as $key => $lang)
                                                        <option value="{{ $key }}"
                                                            {{ old('locale', $row->language) == $key ? 'selected' : '' }}>
                                                            {{ $lang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="template_category"
                                                    class="d-block">{{ __('template_category') }} <span
                                                        class="text-danger">*</span>
                                                    <a href="javascript::void(0)" class="lern-more" data-bs-toggle="modal"
                                                        data-bs-target="#categoryModal">
                                                        <small>{{ __('learn_more_about_categories') }}</small></a></label>
                                                <div
                                                    class="radio_button {{ $row->status == 'APPROVED' ? 'readonly' : '' }}">
                                                    <input type="radio" name="template_category" id="utility"
                                                        value="UTILITY"
                                                        {{ $row->category == 'UTILITY' ? 'checked' : '' }} />
                                                    <label class="btn btn-default" for="utility">
                                                        <i class="las la-bell"></i> {{ __('utility') }}
                                                    </label>
                                                </div>
                                                <div
                                                    class="radio_button {{ $row->status == 'APPROVED' ? 'readonly' : '' }}">
                                                    <input type="radio" id="marketing" name="template_category"
                                                        id="marketing" value="MARKETING"
                                                        {{ $row->category == 'MARKETING' ? 'checked' : '' }} />
                                                    <label class="btn btn-default" for="marketing">
                                                        <i class="las la-bullhorn"></i>
                                                        {{ __('marketing') }}
                                                    </label>
                                                </div>
                                                {{-- <div
                                                    class="radio_button {{ $row->status == 'APPROVED' ? 'readonly' : '' }}">
                                                    <input type="radio" id="authentication" name="template_category"
                                                        id="authentication" value="AUTHENTICATION" class=""
                                                        {{ $row->category == 'AUTHENTICATION' ? 'checked' : '' }} />
                                                    <label class="btn btn-default" for="authentication">
                                                        <i class="las la-lock-open"></i>
                                                        {{ __('auth/otp') }}
                                                    </label>
                                                </div> --}}
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="header_type">{{ __('header_type') }} <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" id="header_type" name="header_type" required>
                                                    <option value="NONE"
                                                        {{ old('header_type') == 'NONE' ? 'selected' : '' }}>
                                                        {{ __('no_header') }}
                                                    </option>
                                                    <option value="TEXT"
                                                        {{ old('header_type', $header['format'] ?? '') == 'TEXT' ? 'selected' : '' }}>
                                                        {{ __('text') }}
                                                    </option>
                                                    <option value="IMAGE"
                                                        {{ old('header_type', $header['format'] ?? '') == 'IMAGE' ? 'selected' : '' }}>
                                                        {{ __('image') }}
                                                    </option>
                                                    <option value="VIDEO"
                                                        {{ old('header_type', $header['format'] ?? '') == 'VIDEO' ? 'selected' : '' }}>
                                                        {{ __('video') }}
                                                    </option>
                                                    <option value="AUDIO"
                                                        {{ old('header_type', $header['format'] ?? '') == 'AUDIO' ? 'selected' : '' }}>
                                                        {{ __('audio') }}
                                                    </option>
                                                    <option value="DOCUMENT"
                                                        {{ old('header_type', $header['format'] ?? '') == 'DOCUMENT' ? 'selected' : '' }}>
                                                        {{ __('document') }}
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div id="headerTextSection" class="mb-4"
                                                style="display:{{ old('header_type', $header['format'] ?? '') == 'TEXT' ? 'block' : 'none' }} ;">
                                                <label for="header_text">{{ __('header_text') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <small class="d-block">
                                                    <italic>{{ __('use_dynamic_variable_like_') }}</italic>
                                                </small>
                                                <input type="text" class="form-control" id="header_text"
                                                    name="header_text" placeholder="{{ __('enter_header_text') }}"
                                                    maxlength="60"
                                                    value="{{ old('header_text', $header['text'] ?? '') }}">
                                                <div class="invalid-feedback text-danger"></div>
                                                <small id="headerCharCount" class="text-muted">{{ __('characters') }}: 0
                                                    / 60</small>

                                                <div id="sample-header" class="sample-header"
                                                    @if (!empty($variables['header'])) style="display: block;" @else  style="display: none;" @endif>
                                                    <div class="card c-card">
                                                        <div class="card-header">
                                                            {{ __('samples_for_header_content') }}
                                                        </div>

                                                        @if (!empty($variables['header']))
                                                            <div class="card-body" id="sample-header-contant">
                                                                @foreach ($variables['header'] as $key => $header_var)
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="variable_{{ $header_var['id'] ?? [] }}">Input
                                                                            for variable {{ $header_var['id'] ?? [] }}<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control"
                                                                            id="variable_{{ $header_var['id'] ?? [] }}"
                                                                            name="header_variable[]"
                                                                            placeholder="Enter value for variable {{ $header_var['id'] ?? [] }}"
                                                                            value="{{ $header_var['exampleValue'] ?? [] }}">
                                                                        <div class="invalid-feedback text-danger"></div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="card-body" id="sample-header-contant"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="headerImageSection" class="mb-4"
                                                style="display: {{ old('header_type', $header['format'] ?? '') == 'IMAGE' ? 'block' : 'none' }};">
                                                <label for="header_image">{{ __('header_image') }}</label>
                                                <input type="file" class="form-control header_file" id="header_image"
                                                    name="header_image" placeholder="{{ __('enter_header_image') }}"
                                                    accept="image/*" value="{{ old('header_image') }}">
                                                <small class="text-muted">{{ __('only_image_file') }}</small>
                                                <!-- Localized help text -->
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>

                                            <div id="headerAudioSection" class="mb-4"
                                                style="display:  {{ old('header_type', $header['format'] ?? '') == 'AUDIO' ? 'block' : 'none' }};">
                                                <label for="header_audio">{{ __('header_audio') }}</label>
                                                <input type="file" class="form-control header_file" id="header_audio"
                                                    name="header_audio" placeholder="{{ __('enter_header_audio') }}"
                                                    accept="audio/*" value="{{ old('header_audio') }}">
                                                <small class="text-muted">{{ __('only_audio_file') }}</small>
                                                <!-- Help text -->
                                                @if ($errors->has('header_audio'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('header_audio') }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div id="headerVideoSection" class="mb-4"
                                                style="display: {{ old('header_type', $header['format'] ?? '') == 'VIDEO' ? 'block' : 'none' }};">
                                                <label for="header_video">{{ __('header_video') }}</label>
                                                <input type="file" class="form-control header_file" id="header_video"
                                                    name="header_video" placeholder="{{ __('enter_header_video') }}"
                                                    accept="video/*" value="{{ old('header_video') }}">
                                                <div id="validationMessage" class="nk-block-des text-danger"></div>

                                                <small class="text-muted">{{ __('only_video_files') }}</small>
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>

                                            <div id="headerDocumentSection" class="mb-4"
                                                style="display: {{ old('header_type', $header['format'] ?? '') == 'DOCUMENT' ? 'block' : 'none' }};">
                                                <label for="header_document">{{ __('header_document') }}</label>
                                                <input type="file" class="form-control header_file"
                                                    id="header_document" name="header_document"
                                                    placeholder="{{ __('enter_header_document') }}" accept=".pdf"
                                                    value="{{ old('header_document') }}">
                                                <small class="text-muted">{{ __('only_pdf_files') }}</small>
                                                <div id="pdfValidationMessage" class="nk-block-des text-danger"></div>
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label for="message_body">{{ __('message_body') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <small class="d-block">
                                                    <italic>{{ __('use_dynamic_variable_like_') }}</italic>
                                                </small>
                                                <textarea class="form-control" id="message_body" maxlength="1024" name="message_body"
                                                    placeholder="{{ __('type_your_message_here') }}" required>{{ old('message_body', $body['text']) }}</textarea>
                                                <div class="invalid-feedback text-danger"></div>
                                                <small id="charCount" class="text-muted">{{ __('characters') }}: 0 /
                                                    1024</small>

                                            </div>

                                            <div id="sample-body" class="sample-body"
                                                @if (!empty($variables['body'])) style="display: block;" @else  style="display: none;" @endif>
                                                <div class="card c-card">
                                                    <div class="card-header">
                                                        {{ __('samples_for_body_content') }}
                                                        <br>
                                                        <span><small>{{ __('template_body_sample_notice') }}</small></span>
                                                    </div>
                                                    @if (!empty($variables['body']))
                                                        <div class="card-body" id="sample-body-contant">
                                                            @foreach ($variables['body'] as $key => $var)
                                                                <div class="form-group">
                                                                    <label for="variable_{{ $var['id'] ?? [] }}">Value for
                                                                        variable {{ $var['id'] ?? [] }}<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control live_preview"
                                                                        id="variable_{{ $var['id'] ?? [] }}"
                                                                        name="body_variable[]"
                                                                        placeholder="Enter value for variable {{ $var['id'] ?? [] }}"
                                                                        value="{{ $var['exampleValue'] ?? [] }}">
                                                                    <div class="invalid-feedback text-danger"></div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="card-body" id="sample-body-contant">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label for="footer_text">{{ __('footer_text') }}</label>
                                                <input type="text" class="form-control" id="footer_text"
                                                    name="footer_text" placeholder="{{ __('enter_footer_text') }}"
                                                    maxlength="60"
                                                    value="{{ old('footer_text', $footer['text'] ?? '') }}">
                                                <small id="footerCharCount" class="text-muted">{{ __('characters') }}: 0
                                                    / 60</small>
                                                <div class="invalid-feedback text-danger"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $button_types = [];
                                    if (isset($buttons) && is_array($buttons)) {
                                        foreach ($buttons as $button) {
                                            if (isset($button['type'])) {
                                                $button_types[] = $button['type'];
                                            }
                                        }
                                        $button_types = array_unique($button_types);
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4 position-relative">
                                                <label for="button_type" class="d-block">{{ __('button') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="radio_button">
                                                    <input type="radio" name="button_type" id="none"
                                                        value="NONE"
                                                        {{ old('button_type') == 'NONE' ? 'checked' : 'checked' }} />
                                                    <label class="btn btn-default" for="none">
                                                        {{ __('none') }}
                                                    </label>
                                                </div>
                                                <div class="radio_button">
                                                    <input type="radio" id="cta" name="button_type"
                                                        id="cta" value="CTA"
                                                        @if (isset($buttons)) @if (in_array('URL', $button_types) || in_array('PHONE_NUMBER', $button_types) || old('button_type') == 'CTA')
                                                            checked @endif
                                                        @endif
                                                    />
                                                    <label class="btn btn-default" for="cta">
                                                        {{ __('cta') }}</label>
                                                </div>
                                                <div class="radio_button">
                                                    <input type="radio" id="quick_reply" name="button_type"
                                                        id="quick_reply" value="QUICK_REPLY"
                                                        @if (isset($buttons)) @if (in_array('QUICK_REPLY', $button_types) || old('button_type') == 'QUICK_REPLY')
                                                            checked @endif
                                                        @endif
                                                    />
                                                    <label class="btn btn-default" for="quick_reply">
                                                        {{ __('quick_reply') }}</label>
                                                </div>
                                                @if ($errors->has('button_type'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('button_type') }}</p>
                                                    </div>
                                                @endif

                                            </div>
                                            <br>
                                            <div id="call-to-action-section" class="position-relative"
                                                style="display:none">
                                                <div class="call-to-action-btn">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="dropdown">
                                                                <button class="btn sg-btn-primary dropdown-toggle btn-sm"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <i class="las la-plus"></i>
                                                                    {{ __('add_call_to_action') }} 
                                                                    
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item add_call_to_action"
                                                                            data-action="visit_website"
                                                                            data-max="2"
                                                                            href="javascript:void(0);">
                                                                            <i class="las la-globe-africa"></i>
                                                                            {{ __('visit_website') }}
                                                                            <span class="d-block add-btn-notice">2
                                                                                {{ __('buttons_maximum') }}</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item add_call_to_action"
                                                                            data-action="call_phone_number"
                                                                            data-max="1"
                                                                            href="javascript:void(0);">
                                                                            <i class="las la-phone-volume"></i>
                                                                            {{ __('call_phone_number') }}
                                                                            <span class="d-block add-btn-notice">1
                                                                                {{ __('buttons_maximum') }}</span>
                                                                        </a>
                                                                    </li>
                                                                    {{-- <li>
                                                                        <a class="dropdown-item add_call_to_action"
                                                                            data-action="copy_offer_code"
                                                                            data-max="1"
                                                                            href="javascript:void(0);">
                                                                            <i class="las la-copy"></i>
                                                                            {{ __('copy_offer_code') }}
                                                                            <span class="d-block add-btn-notice">1
                                                                                {{ __('buttons_maximum') }}</span>
                                                                        </a>
                                                                    </li> --}}
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="append-call-to-action" id="append-call-to-action">
                                                        @if ($buttons)
                                                            @foreach ($buttons as $key=> $button)
                                                                @switch($button['type'])
                                                                    @case('URL')
                                                                        <div class="card mt-2 c-card" data-action="visit_website" id="{{ $key }}">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-3">
                                                                                        <label for="type_of_action"
                                                                                            class="d-block">{{ __('type_of_action') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select name="type_of_action[]"
                                                                                            id="type_of_action"
                                                                                            class="form-select">
                                                                                            <option value="URL">
                                                                                                {{ __('visit_website') }}</option>
                                                                                        </select>
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <label for="button_text"
                                                                                            class="d-block">{{ __('btn_text') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text"
                                                                                            class="form-control button_text_input"
                                                                                            name="button_text[]"
                                                                                            placeholder="{{ __('enter_button_text') }}"
                                                                                            value="{{ $button['text'] }}"
                                                                                            maxlength="20">
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-5">
                                                                                        <label for="website_url"
                                                                                            class="d-block">{{ __('website_url') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="url" class="form-control"
                                                                                            name="button_value[]"
                                                                                            placeholder="{{ __('enter_website_url') }}"
                                                                                            maxlength="2000"
                                                                                            value="{{ $button['url'] }}">
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-1 text-end mt-4">
                                                                                        <button type="button"
                                                                                            class="btn btn-danger text-white remove-card"><i
                                                                                                class="las la-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case('PHONE_NUMBER')
                                                                        <div class="card mt-2 c-card" data-action="call_phone_number" id="{{ $key }}">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-3">
                                                                                        <label for="type_of_action"
                                                                                            class="d-block">{{ __('type_of_action') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select name="type_of_action[]"
                                                                                            id="type_of_action"
                                                                                            class="form-select">
                                                                                            <option value="PHONE_NUMBER">
                                                                                                {{ __('call_phone_number') }}
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <label for="button_text"
                                                                                            class="d-block">{{ __('btn_text') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text"
                                                                                            class="form-control button_text_input"
                                                                                            name="button_text[]"
                                                                                            placeholder="{{ __('enter_button_text') }}"
                                                                                            maxlength="20"
                                                                                            value="{{ $button['text'] }}">
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-5">
                                                                                        <label for="phone_number"
                                                                                            class="d-block">{{ __('phone_number') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text" class="form-control"
                                                                                            name="button_value[]"
                                                                                            placeholder="{{ __('enter_phone_number') }}"
                                                                                            maxlength="20"
                                                                                            value="{{ $button['phone_number'] }}">
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-1 text-end mt-4">
                                                                                        <button type="button"
                                                                                            class="btn btn-danger text-white remove-card"><i
                                                                                                class="las la-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case('COPY_CODE')
                                                                        <div class="card mt-2 c-card" data-action="copy_offer_code" id="{{ $key }}">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-3">
                                                                                        <label for="type_of_action"
                                                                                            class="d-block">{{ __('type_of_action') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select name="type_of_action[]"
                                                                                            id="type_of_action"
                                                                                            class="form-select">
                                                                                            <option value="COPY_CODE">
                                                                                                {{ __('copy_offer_code') }}
                                                                                            </option>
                                                                                        </select>
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                        <input type="hidden"
                                                                                            class="form-control button_text_input"
                                                                                            name="button_text[]"
                                                                                            placeholder="{{ __('enter_button_text') }}"
                                                                                            maxlength="20">
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <label for="button_text"
                                                                                            class="d-block">{{ __('btn_text') }}<span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text"
                                                                                            class="form-control button_text_input"
                                                                                            name="button_value[]"
                                                                                            placeholder="{{ __('enter_button_text') }}"
                                                                                            value="{{ $button['text'] }}">
                                                                                        <div class="invalid-feedback text-danger">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-1 text-end mt-4 ">
                                                                                        <button type="button"
                                                                                            class="btn btn-danger text-white remove-card"><i
                                                                                                class="las la-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break
                                                                @endswitch
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="quick_reply-section" class="position-relative">
                                                <div class="quick_reply-btn">
                                                    <button class="btn sg-btn-primary add-quick-reply-btn btn-sm"
                                                        type="button" data-action="quick_reply" data-max="8">
                                                        <i class="las la-plus"></i> {{ __('add_quick_reply') }}
                                                        <span class="d-block add-btn-notice">{{ __('8') }}
                                                            {{ __('buttons_maximum') }}</span>
                                                    </button>
                                                </div>
                                                <div class="append-quick-reply" id="append-quick-reply">
                                                    @if ($buttons)
                                                        @foreach ($buttons as $key=> $button)
                                                            @switch($button['type'])
                                                                @case('QUICK_REPLY')
                                                                    <div class="card mt-2 c-card" data-action="quick_reply" id="{{ $key }}">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <label for="button_text"
                                                                                        class="d-block">{{ __('btn_text') }}<span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text"
                                                                                        class="form-control button_text_input"
                                                                                        name="button_text[]"
                                                                                        placeholder="{{ __('enter_button_text') }}"
                                                                                        value="{{ $button['text'] }}">
                                                                                    <div class="invalid-feedback text-danger">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-1 text-end mt-4">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger text-white remove-card"><i
                                                                                            class="las la-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @break
                                                            @endswitch
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4 mt-2">
                                                <div class="d-flex justify-content-end align-items-center mt-30">
                                                    <button id="preloader" class="btn btn-primary d-none" type="button"
                                                        disabled>
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </button>
                                                    <button type="submit"
                                                        class="btn btn-primary save">{{ __('submit') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <div class="whatsapp-container">
                                    @include('backend.client.whatsapp.template.partials._preview')
                                </div>
                            </div>
                        </div>
                        @include('backend.client.whatsapp.template.partials._reason_of_rejection')
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.client.whatsapp.template.partials._category_modal')
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script>
        window.translations = {!! json_encode(json_decode(file_get_contents(base_path('lang/en.json')), true)) !!};
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="{{ static_asset('admin/js/custom/template.js') }}?v={{ time() }}"></script>
    <script>
        window.addEventListener('scroll', function() {
            const container = document.querySelector('.whatsapp-container');
            const whatsappPreview = document.querySelector('.whatsapp-preview');

            if (container.getBoundingClientRect().top < 0) {
                whatsappPreview.style.top = '0'; // Stick to top of container when scrolled
            } else {
                whatsappPreview.style.top = '50%'; // Center vertically when not scrolled
            }
        });
    </script>
@endpush
