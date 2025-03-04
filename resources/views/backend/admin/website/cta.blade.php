@extends('backend.layouts.master')
@section('title', __('cta'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('cta') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('admin.cta') }}" method="POST" class="form" enctype="multipart/form-data">@csrf
                            <div class="row gx-20">
                                <input type="hidden" name="site_lang" value="{{$lang}}">
                                <input type="hidden" value="0" class="is_modal" name="is_modal">
                                <!-- End Select Field without search -->
                                <div class="col-12 col-lg-12">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title"
                                                name="cta_title" value="{{ setting('cta_title',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="ctatitle_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <div class="mb-4">
                                        <label for="cta_subtitle" class="form-label">{{ __('subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title"
                                                name="cta_subtitle" value="{{ setting('cta_subtitle',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="ctatitle_error error">{{ $errors->first('cta_subtitle') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="mb-4">
                                        <label for="cta_main_action_btn_label" class="form-label">{{ __('btn_label') }}</label>
                                        <input type="text" class="form-control rounded-2" id="cta_main_action_btn_label"
                                                name="cta_main_action_btn_label" value="{{ setting('cta_main_action_btn_label',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="cta_main_action_btn_label_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-9 col-lg-9">
                                    <div class="mb-4">
                                        <label for="cta_main_action_btn_url" class="form-label">{{ __('btn_url') }}</label>
                                        <input type="text" class="form-control rounded-2" id="cta_main_action_btn_url"
                                                name="cta_main_action_btn_url" value="{{ setting('cta_main_action_btn_url') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="cta_main_action_btn_url_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                    <input type="hidden" name="cta_enable" value="{{ setting('cta_enable') == 1 ? 1 : 0 }}">
                                    <label class="form-label"
                                           for="cta_enable">{{ __('enable') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" value="1" id="cta_enable"
                                               class="sandbox_mode" {{ setting('cta_enable') == 1 ? 'checked' : '' }}>
                                        <label for="cta_enable"></label>
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


