@extends('backend.layouts.master')
@section('title', __('hero_section_content'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('hero_section_content') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('admin.hero.section') }}" method="POST" class="form" enctype="multipart/form-data">@csrf
                            <div class="row gx-20">
                                <input type="hidden" value="0" class="is_modal" name="is_modal">
                                <input type="hidden" name="site_lang" value="{{$lang}}">
                                <div class="col-12 col-lg-12">
                                    <div class="mb-4">
                                        <label for="hero_title" class="form-label">{{ __('title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="hero_title"
                                                name="hero_title" value="{{ setting('hero_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_title_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <div class="mb-4">
                                        <label for="hero_subtitle" class="form-label">{{ __('subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2" id="hero_subtitle"
                                                name="hero_subtitle" value="{{ setting('hero_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_subtitle_error error">{{ $errors->first('hero_subtitle') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="hero_description" class="form-label">{{ __('description') }}</label>
                                        <textarea class="form-control" id="hero_description"
                                                  name="hero_description">{{ setting('hero_description', $lang) }}</textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_description_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                    <input type="hidden" name="hero_main_action_btn_enable" value="{{ setting('hero_main_action_btn_enable') == 1 ? 1 : 0 }}">
                                    <label class="form-label"
                                           for="hero_main_action_btn_enable">{{ __('action_btn') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" value="1" id="hero_main_action_btn_enable"
                                               class="sandbox_mode" {{ setting('hero_main_action_btn_enable') == 1 ? 'checked' : '' }}>
                                        <label for="hero_main_action_btn_enable"></label>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="mb-4">
                                        <label for="hero_main_action_btn_label" class="form-label">{{ __('btn_label') }}</label>
                                        <input type="text" class="form-control rounded-2" id="hero_main_action_btn_label"
                                                name="hero_main_action_btn_label" value="{{ setting('hero_main_action_btn_label', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_main_action_btn_label_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-9 col-lg-9">
                                    <div class="mb-4">
                                        <label for="hero_main_action_btn_url" class="form-label">{{ __('btn_url') }}</label>
                                        <input type="text" class="form-control rounded-2" id="hero_main_action_btn_url"
                                                name="hero_main_action_btn_url" value="{{ setting('hero_main_action_btn_url') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_main_action_btn_url_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                    <input type="hidden" name="hero_secondary_action_btn_enable" value="{{ setting('hero_secondary_action_btn_enable') == 1 ? 1 : 0 }}">
                                    <label class="form-label"
                                           for="hero_secondary_action_btn_enable">{{ __('secondary_action_btn') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" value="1" id="hero_secondary_action_btn_enable"
                                               class="sandbox_mode" {{ setting('hero_secondary_action_btn_enable') == 1 ? 'checked' : '' }}>
                                        <label for="hero_secondary_action_btn_enable"></label>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="mb-4">
                                        <label for="hero_secondary_action_btn_label" class="form-label">{{ __('secondary_btn_label') }}</label>
                                        <input type="text" class="form-control rounded-2" id="hero_secondary_action_btn_label"
                                                name="hero_secondary_action_btn_label" value="{{ setting('hero_secondary_action_btn_label', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_secondary_action_btn_label_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-9 col-lg-9">
                                    <div class="mb-4">
                                        <label for="hero_secondary_action_btn_url" class="form-label">{{ __('secondary_btn_url') }}</label>
                                        <input type="text" class="form-control rounded-2" name="hero_secondary_action_btn_url" id="hero_secondary_action_btn_url"
                                               value="{{ setting('hero_secondary_action_btn_url') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="hero_secondary_action_btn_url_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 input_file_div mb-3">
                                    <div class="mb-3">
                                        <label for="image1" class="form-label mb-1">{{ __('image') }}</label>
                                        <label for="image1" class="file-upload-text">
                                            <p>0 File Choosen</p>
                                            <span class="file-btn">{{ __('choose_file') }}</span>
                                        </label>
                                        <input class="d-none file_picker" type="file" name="header1_hero_image1" id="image1" accept=".jpg,.png">
                                        <div class="nk-block-des text-danger">
                                            <p class="header1_hero_image1_error error">{{ $errors->first('header1_hero_image1') }}</p>
                                        </div>
                                    </div>
                                    <div class="selected-files d-flex flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img class="selected-img" src="{{  getFileLink('80x80',setting('header1_hero_image1')) }}" alt="favicon">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start align-items-center mt-30">
                                    <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.common.gallery-modal')
@endsection
@push('js')

@endpush
@push('css_asset')
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush


