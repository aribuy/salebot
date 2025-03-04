@extends('backend.layouts.master')
@section('title', __('admin_panel_setting'))
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col col-lg-8 col-md-9">
                <h3 class="section-title">{{ __('admin_panel_setting') }}</h3>
                <div class="bg-white redious-border pt-30 p-20 p-sm-30">
                    <div class="section-top">
                        <h6>{{ __('admin_panel_setting') }}</h6>
                    </div>
                    <form action="{{ route('admin.panel-setting.update') }}" method="post"  enctype="multipart/form-data">@csrf
                        <input type="hidden" name="r" value="{{ url()->current() }}" class="r">
                        <div class="row gx-20">
                            <div class="col-lg-12 input_file_div mb-4">
                                <div class="mb-3">
                                    <label class="form-label mb-1">{{__('logo') }} (100X36)</label>
                                    <label for="admin_logo"
                                           class="file-upload-text">
                                        <p></p>
                                        <span class="file-btn">{{__('choose_file') }}</span></label>
                                    <input class="d-none file_picker" type="file" id="admin_logo"  name="admin_logo" accept=".jpg,.png">
                                    <div class="nk-block-des text-danger">
                                        <p class="admin_logo_error error">{{ $errors->first('admin_logo') }}</p>
                                    </div>
                                </div>
                                <div class="selected-files d-flex flex-wrap gap-20">
                                    <div class="selected-files-item">
                                        <img class="selected-img" src="{{ getFileLink('80x80',setting('admin_logo')) }}" alt="admin_logo">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 input_file_div mb-4">
                                <div class="mb-3">
                                    <label class="form-label mb-1">{{__('mini_logo') }} (1:1)</label>
                                    <label for="admin_mini_logo"
                                           class="file-upload-text">
                                           <p></p>
                                           <span class="file-btn">{{__('choose_file') }}</span></label>
                                    <input class="d-none file_picker" type="file" id="admin_mini_logo" name="admin_mini_logo" accept=".jpg,.png">
                                    <div class="nk-block-des text-danger">
                                        <p class="admin_mini_logo_error error">{{ $errors->first('admin_mini_logo') }}</p>
                                    </div>
                                </div>
                                <div class="selected-files d-flex flex-wrap gap-20">
                                    <div class="selected-files-item">
                                        <img class="selected-img" src="{{ getFileLink('80x80',setting('admin_mini_logo')) }}" alt="admin_mini_logo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 input_file_div mb-4">
                                <div class="mb-3">
                                    <label class="form-label mb-1">{{__('favicon') }}</label>
                                    <label for="admin_favicon" class="file-upload-text">
                                        <p></p>
                                        <span class="file-btn">{{__('choose_file') }}</span>
                                    </label>
                                    <input class="d-none file_picker" type="file" id="admin_favicon" name="admin_favicon" accept=".jpg,.png">
                                    <div class="nk-block-des text-danger">
                                        <p class="admin_favicon_error error">{{ $errors->first('admin_favicon') }}</p>
                                    </div>
                                </div>
                                <div class="selected-files d-flex flex-wrap gap-20">
                                    <div class="selected-files-item">
                                        @php
                                            $icon = setting('admin_favicon');
                                        @endphp
                                        @if($icon)
                                            <img class="selected-img" src="{{ ($icon != [] && @is_file_exists($icon['image_96x96_url'])) ? static_asset($icon['image_96x96_url']) : static_asset('images/default/favicon/favicon-96x96.png') }}" alt="favicon">
                                        @else
                                            <img class="selected-img" src="{{ static_asset('images/default/favicon/favicon-96x96.png') }}" alt="favicon">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="admin_panel_copyright_text" class="form-label">{{ __('copyright_text') }}</label>
                                    <input type="text" class="form-control rounded-2" id="admin_panel_copyright_text"
                                           placeholder="{{__('copyright_text') }}" name="admin_panel_copyright_text" value="{{ old('admin_panel_copyright_text', setting('admin_panel_copyright_text') ) }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="admin_panel_copyright_text_error error"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start align-items-center">
                            <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                            @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $(document).on('change', '#default_storage', function () {
                var storage = $(this).val();
                if (storage == 'aws_s3') {
                    $('.aws_div').removeClass('d-none');
                    $('.wasabi_div').addClass('d-none');
                } else if (storage == 'wasabi') {
                    $('.aws_div').addClass('d-none');
                    $('.wasabi_div').removeClass('d-none');
                } else {
                    $('.aws_div').addClass('d-none');
                    $('.wasabi_div').addClass('d-none');
                }
            });
        });
    </script>
@endpush
