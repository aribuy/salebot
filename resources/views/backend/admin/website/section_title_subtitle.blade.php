@extends('backend.layouts.master')
@section('title', __('header_menu'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('section_title_subtitle') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('admin.section_title_subtitle') }}" method="POST" class="form">
                            @csrf
                            <input type="hidden" name="site_lang" value="{{ $lang }}">
                            <div class="pageTitle">
                                <h6 class="sub-title">{{ __('section_title') }}</h6>
                            </div>
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" class="is_modal" value="0" />
                                @if (active_theme() !=='darkbot')
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="client_section_title"
                                            class="form-label">{{ __('client_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="client_section_title" name="client_section_title"
                                            value="{{ setting('client_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="client_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if (active_theme() !== 'martex' && active_theme() !== 'darkbot')
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="story_section_title"
                                                class="form-label">{{ __('story_section_title') }}</label>
                                            <input type="text" class="form-control rounded-2 ai_content_name"
                                                id="story_section_title" name="story_section_title"
                                                value="{{ setting('story_section_title', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="story_section_title_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="story_section_subtitle"
                                                class="form-label">{{ __('story_section_subtitle') }}</label>
                                            <input type="text" class="form-control rounded-2 ai_content_name"
                                                id="story_section_subtitle" name="story_section_subtitle"
                                                value="{{ setting('story_section_subtitle', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="story_section_subtitle_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (active_theme() == 'darkbot')
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="growth_section_title"
                                            class="form-label">{{ __('growth_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="growth_section_title" name="growth_section_title"
                                            value="{{ setting('growth_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="growth_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="growth_section_subtitle"
                                            class="form-label">{{ __('growth_section_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="growth_section_subtitle" name="growth_section_subtitle"
                                            value="{{ setting('growth_section_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="growth_section_subtitle_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if (active_theme() !== 'martex')
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="unique_feature_section_title"
                                                class="form-label">{{ __('unique_feature_section_title') }}</label>
                                            <input type="text" class="form-control rounded-2 ai_content_name"
                                                id="unique_feature_section_title" name="unique_feature_section_title"
                                                value="{{ setting('unique_feature_section_title', $lang) }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="unique_feature_section_title_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif



                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="feature_section_title"
                                            class="form-label">{{ __('feature_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="feature_section_title" name="feature_section_title"
                                            value="{{ setting('feature_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="feature_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="feature_section_subtitle"
                                            class="form-label">{{ __('feature_section_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="feature_section_subtitle" name="feature_section_subtitle"
                                            value="{{ setting('feature_section_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="feature_section_subtitle_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="testimonial_section_title"
                                            class="form-label">{{ __('testimonial_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="testimonial_section_title" name="testimonial_section_title"
                                            value="{{ setting('testimonial_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="testimonial_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="testimonial_section_subtitle"
                                            class="form-label">{{ __('testimonial_section_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="testimonial_section_subtitle" name="testimonial_section_subtitle"
                                            value="{{ setting('testimonial_section_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="testimonial_section_subtitle_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="pricing_section_title"
                                            class="form-label">{{ __('pricing_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="pricing_section_title" name="pricing_section_title"
                                            value="{{ setting('pricing_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="pricing_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="pricing_section_subtitle"
                                            class="form-label">{{ __('pricing_section_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="pricing_section_subtitle" name="pricing_section_subtitle"
                                            value="{{ setting('pricing_section_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="pricing_section_subtitle_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="advantage_section_title"
                                            class="form-label">{{ __('advantage_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="advantage_section_title" name="advantage_section_title"
                                            value="{{ setting('advantage_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="advantage_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="advantage_section_subtitle"
                                            class="form-label">{{ __('advantage_section_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="advantage_section_subtitle" name="advantage_section_subtitle"
                                            value="{{ setting('advantage_section_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="advantage_section_subtitle_error error"></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="faq_section_title"
                                            class="form-label">{{ __('faq_section_title') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="faq_section_title" name="faq_section_title"
                                            value="{{ setting('faq_section_title', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="faq_section_title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="faq_section_subtitle"
                                            class="form-label">{{ __('faq_section_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2 ai_content_name"
                                            id="faq_section_subtitle" name="faq_section_subtitle"
                                            value="{{ setting('faq_section_subtitle', $lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="faq_section_subtitle_error error"></p>
                                        </div>
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
    </section>
    @include('backend.admin.website.component.new_menu')
@endsection
@push('js_asset')
    <script src="{{ static_asset('admin/js/jquery.nestable.min.js') }}"></script>
@endpush
@push('js')
@endpush
