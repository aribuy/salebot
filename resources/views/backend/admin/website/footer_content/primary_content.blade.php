@extends('backend.layouts.master')
@section('title', __('primary_content'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('primary_content') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border activeItem-bd-none p-20 p-sm-30">
                    @include('backend.admin.website.component.footer_setting_sidebar')
                        <form action="{{ route('footer.update-setting') }}" method="POST" class="form">@csrf
                            <input type="hidden" name="site_lang" value="{{$lang}}">
                            <div class="row gx-20">
                                <div class="col-6">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <label for="contact_email" class="form-label">{{ __('contact_email') }}</label>
                                        </div>
                                        <input type="email" class="form-control rounded-2" id="contact_email" name="contact_email"
                                               value="{{ setting('contact_email', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="contact_email_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label for="contact_phone" class="form-label">{{__('contact_phone') }}</label>
                                        <input type="text" class="form-control rounded-2" id="contact_phone" name="contact_phone"
                                                value="{{ setting('contact_phone', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="contact_phone_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between">
                                            <label for="high_lighted_text" class="form-label">{{ __('high_lighted_text') }}</label>
                                            @include('backend.common.ai_btn',[
                                                    'name' => 'ai_short_high_lighted_text',
                                                    'length' => '200',
                                                    'topic' => 'ai_content_name',
                                                    'use_case' => 'short testimonial high_lighted_text for an learning website',
                                                    ])
                                        </div>
                                        <textarea class="form-control ai_short_high_lighted_text" id="high_lighted_text" name="high_lighted_text">{{ setting('high_lighted_text', $lang) }}</textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="high_lighted_text_error error"></p>
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
