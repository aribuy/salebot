@extends('backend.layouts.master')
@section('title', __('ai_chat'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('ai_chat') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('admin.ai.chat') }}" method="POST" class="form" enctype="multipart/form-data">@csrf
                            <input type="hidden" name="site_lang" value="{{$lang}}">
                            <div class="row gx-20">
                                <input type="hidden" value="0" class="is_modal" name="is_modal">
                                <!-- End Select Field without search -->
                                <div class="col-12 col-lg-12">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="ai_title" name="ai_title" value="{{ setting('ai_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="title_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="description" class="form-label">{{ __('description') }}</label>
                                        <textarea class="form-control" id="ai_description"
                                                  name="ai_description">{{ setting('ai_description', $lang) }}</textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="description_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="mb-4">
                                        <label for="main_action_btn_label" class="form-label">{{ __('btn_label') }}</label>
                                        <input type="text" class="form-control rounded-2" id="ai_main_action_btn_label"
                                                name="ai_main_action_btn_label" value="{{ setting('ai_main_action_btn_label', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="main_action_btn_label_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-9">
                                    <div class="mb-4">
                                        <label for="main_action_btn_url" class="form-label">{{ __('btn_url') }}</label>
                                        <input type="text" class="form-control rounded-2" id="ai_main_action_btn_url"
                                                name="ai_main_action_btn_url" value="{{ setting('ai_main_action_btn_url') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="main_action_btn_url_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 input_file_div mb-3">
                                    <div class="mb-3">
                                        <label for="image1" class="form-label mb-1">{{ __('qr_image') }}</label>
                                        <label for="image1" class="file-upload-text"><span>{{ __('choose_file') }}</span></label>
                                        <input class="d-none file_picker" type="file" name="qr_image" id="image1" accept=".jpg,.png">
                                        <div class="nk-block-des text-danger">
                                            <p class="qr_image_error error">{{ $errors->first('qr_image') }}</p>
                                        </div>
                                    </div>
                                    <div class="selected-files d-flex flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img class="selected-img" src="{{  getFileLink('80x80',setting('qr_image')) }}" alt="favicon">
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
@endsection


