
@extends('backend.layouts.master')
@section('title', __('add_new_faq'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('add_new_faq') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('faqs.store') }}" method="POST" class="form">@csrf
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" class="is_modal" value="0"/>
                                <input type="hidden" value="{{ $lang }}" name="lang">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="question" class="form-label">{{ __('question') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="question" name="question"required>
                                        <div class="nk-block-des text-danger">
                                            <p class="question_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="order" class="form-label">{{ __('order') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="order" name="ordering"
                                               placeholder="{{ __('e.g.5') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="ordering_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="editor-wrapper">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label mb-1">{{ __('answer') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <textarea id="product-update-editor" class="description"
                                                  name="answer" required></textarea>
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
@endsection

