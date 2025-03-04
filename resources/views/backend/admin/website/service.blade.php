@extends('backend.layouts.master')
@section('title', __('services'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('services') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                        <div class="row">
                            <form action="{{ route('website.service.update') }}" class="form-validate form" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="py-3 border-bottom mb-2">{{ __('service_1') }}</h5>
                                    </div>
                                    <input type="hidden" value="{{ $lang }}" name="lang">
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="service_1_title" class="form-label">{{ __('title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="service_1_title"
                                                name="service_1_title" value="{{ setting('service_1_title', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_1_title_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="service_1_subtitle" class="form-label">{{ __('subtitle') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="service_1_subtitle"
                                                name="service_1_subtitle" value="{{ setting('service_1_subtitle', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_1_subtitle_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="editor-wrapper">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label mb-1">{{ __('description') }}</label>
                                            </div>
                                            <textarea id="service_1_description" class="description summernote" name="service_1_description">{{ setting('service_1_description', $lang) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('image') }} (645X840)</label>
                                            <label for="service_1_image" class="file-upload-text">
                                                <p>0 File Choosen</p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="service_1_image"
                                                name="service_1_image" accept=".jpg,.png">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_1_image_error error">
                                                    {{ $errors->first('service_1_image') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img"
                                                    src="{{ getFileLink('original_image', setting('service_1_image')) }}"
                                                    alt="service_1_image">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <h5 class="py-3 border-bottom mb-2">{{ __('service_2') }}</h5>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="service_2_title" class="form-label">{{ __('title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="service_3_title"
                                                name="service_2_title" value="{{ setting('service_2_title', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_3_title_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="service_2_subtitle" class="form-label">{{ __('subtitle') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="service_2_subtitle"
                                                name="service_2_subtitle"
                                                value="{{ setting('service_2_subtitle', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_2_subtitle_error error"></p>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col-lg-12">
                                        <div class="editor-wrapper">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label mb-1"></label>
                                            </div>
                                            <textarea id="service_2_description" class="description summernote" name="service_2_description">{{ setting('service_2_description', $lang) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('image') }} (665X720)</label>
                                            <label for="service_2_image" class="file-upload-text">
                                                <p>0 File Choosen</p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="service_2_image"
                                                name="service_2_image" accept=".jpg,.png">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_2_image_error error">
                                                    {{ $errors->first('service_2_image') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img"
                                                    src="{{ getFileLink('original_image', setting('service_2_image')) }}"
                                                    alt="service_2_image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h5 class="py-3 border-bottom mb-2">{{ __('service_3') }}</h5>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="service_3_title" class="form-label">{{ __('title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="service_3_title"
                                                name="service_3_title" value="{{ setting('service_3_title', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_3_title_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="service_3_subtitle" class="form-label">{{ __('subtitle') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="service_3_subtitle"
                                                name="service_3_subtitle"
                                                value="{{ setting('service_3_subtitle', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_3_subtitle_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="editor-wrapper">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label mb-1">{{ __('description') }}</label>
                                            </div>
                                            <textarea id="service_3_description" class="description summernote" name="service_3_description">{{ setting('service_3_description', $lang) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('image') }} (675X765)</label>
                                            <label for="service_3_image" class="file-upload-text">
                                                <p>0 File Choosen</p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="service_3_image"
                                                name="service_3_image" accept=".jpg,.png">
                                            <div class="nk-block-des text-danger">
                                                <p class="service_3_image_error error">
                                                    {{ $errors->first('service_3_image') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img"
                                                    src="{{ getFileLink('original_image', setting('service_3_image')) }}"
                                                    alt="service_3_image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mt-30">
                                        <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
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
