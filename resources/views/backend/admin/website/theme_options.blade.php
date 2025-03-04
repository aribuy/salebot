@extends('backend.layouts.master')
@section('title', __('theme_options'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('theme_options') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                            <form action="{{ route('admin.theme.options') }}" method="POST" class="form"
                                enctype="multipart/form-data">@csrf
                                <input type="hidden" name="menu_name" value="header_menu">
                                <div class="row gx-20">
                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('light_logo') }} (100X36)</label>
                                            <label for="light_logo" class="file-upload-text">
                                                <p>0 File Choosen</p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="light_logo"
                                                name="light_logo" accept=".jpg,.png">
                                            <div class="nk-block-des text-danger">
                                                <p class="light_logo_error error">{{ $errors->first('light_logo') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img"
                                                    src="{{ getFileLink('original_image', setting('light_logo')) }}"
                                                    alt="light_logo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('dark_logo') }} (100X36)</label>
                                            <label for="dark_logo" class="file-upload-text">
                                                <p>0 File Choosen</p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="dark_logo" name="dark_logo"
                                                accept=".jpg,.png">
                                            <div class="nk-block-des text-danger">
                                                <p class="dark_logo_error error">{{ $errors->first('dark_logo') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img"
                                                    src="{{ getFileLink('original_image', setting('dark_logo')) }}"
                                                    alt="dark_logo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('icon') }}</label>
                                            <label for="favicon" class="file-upload-text">
                                                <p>0 File Choosen</p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="favicon" name="favicon"
                                                accept=".jpg,.png">
                                            <div class="nk-block-des text-danger">
                                                <p class="favicon_error error">{{ $errors->first('favicon') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                @php
                                                    $icon = setting('favicon');
                                                @endphp
                                                @if ($icon)
                                                    <img class="selected-img"
                                                        src="{{ $icon != [] && @is_file_exists($icon['image_96x96_url']) ? static_asset($icon['image_96x96_url']) : static_asset('images/default/favicon/favicon-96x96.png') }}"
                                                        alt="favicon">
                                                @else
                                                    <img class="selected-img"
                                                        src="{{ static_asset('images/default/favicon/favicon-96x96.png') }}"
                                                        alt="favicon">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="select-type-v2 mb-4">
                                            <label class="form-label">{{ __('headline_font') }}</label>
                                            <select class="form-select form-select-lg mb-3 with_search" name="header_font"
                                                id="header_font">
                                                <option value="">{{ __('select_fonts') }}</option>
                                                @foreach (google_fonts_list() as $font_name => $font_family)
                                                    <option value="{{ $font_name }}" data-family="{{ $font_family }}"
                                                        {{ setting('header_font') == $font_name ? 'selected' : '' }}>
                                                        <span>{{ $font_family }}</span>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="select-type-v2 mb-4">
                                            <label class="form-label">{{ __('theme') }}</label>
                                            <select class="form-select form-select-lg mb-3 with_search" name="active_theme"
                                                id="active_theme">
                                                @foreach ($foldernames as $folderName)
                                                    <option value="{{ $folderName }}"
                                                        {{ setting('active_theme') == $folderName ? 'selected' : '' }}>
                                                        {{ __($folderName) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="select-type-v2 mb-4">
                                            <label class="form-label">{{ __('body_font') }}</label>
                                            <select class="form-select form-select-lg mb-3 with_search" name="body_font"
                                                id="body_font">
                                                <option value="">{{ __('select_fonts') }}</option>
                                                @foreach (google_fonts_list() as $font_name => $font_family)
                                                    <option value="{{ $font_name }}" data-family="{{ $font_family }}"
                                                        {{ setting('body_font') == $font_name ? 'selected' : '' }}>
                                                        <span>{{ $font_family }}</span>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="select-type-v2 mb-4">
                                            <label class="form-label">{{ __('disable_landing_page') }}</label>
                                            <select class="form-select form-select-lg mb-3 with_search"
                                                name="disable_landing_page" id="disable_landing_page">
                                                <option value="0"
                                                    {{ setting('disable_landing_page') == 0 ? 'selected' : 'selected' }}>
                                                    <span>{{ __('no') }}</span>
                                                </option>
                                                <option value="1"
                                                    {{ setting('disable_landing_page') == 1 ? 'selected' : '' }}>
                                                    <span>{{ __('yes') }}</span>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                        <label class="form-label" for="language_switcher">{{ __('language_switcher') }}</label>
                                        <div class="setting-check">
                                            <input type="hidden" name="language_switcher" value="0">
                                            <input type="checkbox" value="1" name="language_switcher"
                                                id="language_switcher" class="sandbox_mode"
                                                {{ (setting('language_switcher') == 1 || setting('language_switcher') === null) ? 'checked' : '' }}>
                                            <label for="language_switcher"></label>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-30">
                                        <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                                        @include('backend.common.loading-btn', [
                                            'class' => 'btn sg-btn-primary',
                                        ])
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.admin.website.component.new_menu')
@endsection
