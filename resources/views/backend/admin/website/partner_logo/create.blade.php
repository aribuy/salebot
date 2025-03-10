@extends('backend.layouts.master')
@section('title', __('partner_logo'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('add_new_partner_logo') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('partner-logo.store') }}" method="POST" class="form" enctype="multipart/form-data">
                            @csrf
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" class="is_modal" value="0"/>
                                <input type="hidden" value="{{ $lang }}" name="lang">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="name" class="form-label">{{ __('name') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name" id="name" name="name">
                                        <div class="nk-block-des text-danger">
                                            <p class="name_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 input_file_div">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">{{__('image') }}</label>
                                        <label for="image" class="file-upload-text">
                                            <p></p>
                                            <span class="file-btn">{{__('choose_file') }}</span>
                                        </label>
                                        <input class="d-none file_picker" type="file" id="image"
                                               name="image" accept=".jpg,.png">
                                        <div class="nk-block-des text-danger">
                                            <p class="image_error error">{{ $errors->first('image') }}</p>
                                        </div>
                                    </div>
                                    <div class="selected-files d-flex flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img class="selected-img" src="{{ getFileLink('80x80',[]) }}"
                                                 alt="favicon">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end align-items-center mt-30">
                                    <button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.admin.website.component.new_menu')
    @include('backend.common.gallery-modal')
@endsection
@push('css_asset')
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@push('js_asset')
    <!--====== media.js ======-->
    <script src="{{ static_asset('admin/js/ai_writer.js') }}"></script>
@endpush
