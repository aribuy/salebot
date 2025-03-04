@extends('backend.layouts.master')
@section('title', __('general_setting'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('system_setting') }}</h3>
                <div class="bg-white redious-border pt-30 p-20 p-sm-30">
                    <div class="section-top">
                        <h6>{{ __('general_setting') }}</h6>
                        <div class="lang-select">
                            <form id="lang">
                                <input type="hidden" name="r" value="{{ url()->current() }}" class="r">
                                <div class="select-type-v2 mb-40">
                                    <select class="form-select form-select-lg mb-3 with_search lang" name="site_lang">
                                        @foreach (app('languages') as $language)
                                            <option value="{{ $language->locale }}"
                                                {{ $language->locale == $lang ? 'selected' : '' }}>{{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('general.setting-update') }}" method="post" enctype="multipart/form-data">@csrf
                        <input type="hidden" name="site_lang" value="{{ $lang }}">
                        <div class="row gx-20">
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="system_title" class="form-label">{{ __('system_title') }}<span
                                                class="text-danger">*</span></label>
                                    <input type="text" name="system_name" class="form-control rounded-2"
                                        id="system_title" value="{{ old('system_name',setting('system_name', $lang)) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="system_name_error error">{{ $errors->first('system_name') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="companyName" class="form-label">{{ __('company_name') }}<span
                                                class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control rounded-2"
                                        id="companyName" value="{{ old('company_name',setting('company_name', $lang)) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="company_name_error error">{{ $errors->first('company_name') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="tagline" class="form-label">{{ __('tagline') }}</label>
                                    <input id="tagline" type="text" name="tagline" class="form-control rounded-2"
                                        value="{{ old('tagline',setting('tagline', $lang)) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="tagline_error error">{{ $errors->first('tagline') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                @include('backend.common.tel-input', [
                                    'name' => 'phone',
                                    'value' => isDemoMode() ? '******************' : old('phone',setting('phone')),
                                    'label' => __('phone_number'),
                                    'id' => 'phone_number',
                                    'country_id_field' => 'phone_country_id',
                                    'country_id' =>
                                        setting('phone') && setting('phone_country_id')
                                            ? setting('phone_country_id')
                                            : (setting('default_country') ?:
                                            19),
                                ])
                            </div>
                        </div>
                        <div class="row gx-20">

                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="emailAddress" class="form-label">{{ __('email_address') }}<span
                                                class="text-danger">*</span></label>
                                    <input type="email" class="form-control rounded-2" id="emailAddress"
                                        name="email_address" value="{{ old('email_address',setting('email_address')) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="email_address_error error">{{ $errors->first('email_address') }}</p>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="activation_code" class="form-label">{{ __('activation_code') }}<span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="activation_code"
                                        name="activation_code" value="{{ isDemoMode() ? '******************' : old('activation_code',setting('activation_code')) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="purchase_code_error error">{{ $errors->first('activation_code') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="time_zone" class="form-label">{{ __('time_zone') }}</label>
                                    <select class="form-select form-select-lg mb-3 with_search" name="time_zone"
                                        id="time_zone">
                                        @foreach ($time_zones as $time_zone)
                                            <option 
                                            value="{{ $time_zone->id}}"
                                                {{ $time_zone->id == old('time_zone',setting('time_zone')) ? 'selected' : '' }}>
                                                {{ $time_zone->gmt_offset > 0 ? "(UTC +$time_zone->gmt_offset)" . ' ' . $time_zone->timezone : "(UTC $time_zone->gmt_offset)" .' '. $time_zone->timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="nk-block-des text-danger">
                                        <p class="time_zone_error error">{{ $errors->first('time_zone') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="defaultLAN" class="form-label">{{ __('default_language') }}</label>
                                    <div class="select-type-v2">
                                        <select class="form-select form-select-lg mb-3 without_search"
                                            name="default_language" id="defaultLAN">
                                            @foreach ($languages as $language)
                                                <option value="{{ $language->locale }}"
                                                    {{ $language->locale == old('default_language',setting('default_language')) ? 'selected' : '' }}>
                                                    {{ $language->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="country" class="form-label">{{ __('country') }}</label>
                                    <div class="select-type-v2">
                                        <select class="form-select form-select-lg mb-3 with_search" name="default_country"
                                            id="country">
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ $country->id == old('default_country',setting('default_country')) ? 'selected' : '' }}>
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="currency" class="form-label">{{ __('default_currency') }}</label>
                                    <div class="select-type-v2">
                                        <select class="form-select form-select-lg mb-3 with_search"
                                            name="default_currency" id="currency">
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->code }}"
                                                    {{ $currency->code == old('default_currency',setting('default_currency')) ? 'selected' : '' }}>
                                                    {{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-4">
                                    <label for="primary_color" class="form-label">{{ __('primary_color') }}<span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="primary_color"
                                           name="primary_color" value="{{ old('primary_color',setting('primary_color')) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="primary_color_error error">{{ $errors->first('primary_color') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 input_file_div">
                                <div class="mb-3">
                                    <label for="logoUpload" class="form-label mb-1">{{ __('website_favicon') }}
                                        (96x96)</label>
                                    <label for="logoUpload" class="file-upload-text">
                                        <p></p>
                                        <span class="file-btn">{{ __('choose_file') }}</span>
                                    </label>
                                    <input class="d-none file_picker" type="file" name="favicon" id="logoUpload" accept=".jpg,.png">
                                    <div class="nk-block-des text-danger">
                                        <p class="favicon_error error">{{ $errors->first('favicon') }}</p>
                                    </div>
                                </div>
                                <div class="selected-files d-flex flex-wrap gap-20">
                                    <div class="selected-files-item">
                                        <img class="selected-img"
                                            src="{{ setting('favicon') && @is_file_exists(setting('favicon')['image_96x96_url']) ? get_media(setting('favicon')['image_96x96_url']) : getFileLink('80x80', []) }}"
                                            alt="favicon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="mb-4">
                                    <label for="api_documentation_url" class="form-label">{{ __('api_documentation_url') }}</label>
                                    <input id="api_documentation_url" type="text" name="api_documentation_url" class="form-control rounded-2"
                                           value="{{ old('api_documentation_url',setting('api_documentation_url')) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="api_documentation_url_error error">{{ $errors->first('api_documentation_url') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="mb-4">
                                    <label for="whatsApp_settings_video_url" class="form-label">{{ __('whatsApp_settings_video_url') }}</label>
                                    <input id="whatsApp_settings_video_url" type="url" name="whatsApp_settings_video_url" class="form-control rounded-2"
                                           value="{{ old('whatsApp_settings_video_url',setting('whatsApp_settings_video_url')) }}" placeholder="https://www.youtube.com/watch?v=ipRTUj4LjOo">
                                    <div class="nk-block-des text-danger">
                                        <p class="whatsApp_settings_video_url_error error">{{ $errors->first('whatsApp_settings_video_url') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="mb-4">
                                    <label for="telegram_settings_video_url" class="form-label">{{ __('telegram_settings_video_url') }}</label>
                                    <input id="telegram_settings_video_url" type="url" name="telegram_settings_video_url" class="form-control rounded-2"
                                           value="{{ old('telegram_settings_video_url',setting('telegram_settings_video_url')) }}" placeholder="https://www.youtube.com/watch?v=ipRTUj4LjOo">
                                    <div class="nk-block-des text-danger">
                                        <p class="telegram_settings_video_url_error error">{{ $errors->first('telegram_settings_video_url') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-30">
                                <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
                                @include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ static_asset('admin/js/countries.js') }}"></script>
@endpush
