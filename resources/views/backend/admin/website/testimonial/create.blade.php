
@extends('backend.layouts.master')
@section('title', __('testimonial'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('add_new_testimonial') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('testimonials.store') }}" method="POST" class="form" enctype="multipart/form-data">@csrf
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" value="{{ $lang }}" name="lang">
                                <input type="hidden" class="is_modal" value="0"/>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="name" class="form-label">{{ __('name') }}</label>
                                        <input type="text" class="form-control rounded-2 " id="name" name="name">
                                        <div class="nk-block-des text-danger">
                                            <p class="name_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('title') }}</label>
                                        <input type="text" class="form-control rounded-2 " id="title" name="title">
                                        <div class="nk-block-des text-danger">
                                            <p class="title_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="designation" class="form-label">{{ __('designation') }}</label>
                                        <input type="text" class="form-control rounded-2 " id="designation" name="designation">
                                        <div class="nk-block-des text-danger">
                                            <p class="designation_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between">
                                            <label for="description" class="form-label">{{ __('description') }}</label>
                                            @include('backend.common.ai_btn',[
                                                    'name' => 'ai_short_description',
                                                    'length' => '200',
                                                    'topic' => 'ai_content_name',
                                                    'use_case' => 'short testimonial description for an learning website',
                                                    ])
                                        </div>
                                        <textarea class="form-control ai_short_description" id="description" name="description"></textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="description_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 input_file_div mb-4">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">{{__('avatar') }}</label>
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
                                <div class="col-lg-12 input_file_div mb-4">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">{{__('logo') }}</label>
                                        <label for="logo" class="file-upload-text">
                                            <p></p>
                                            <span class="file-btn">{{__('choose_file') }}</span>
                                        </label>
                                        <input class="d-none file_picker" type="file" id="logo"
                                               name="logo" accept=".jpg,.png">
                                        <div class="nk-block-des text-danger">
                                            <p class="image_error error">{{ $errors->first('logo') }}</p>
                                        </div>
                                    </div>
                                    <div class="selected-files d-flex flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img class="selected-img" src="{{ getFileLink('80x80',[]) }}"
                                                 alt="logo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="star" class="form-label">{{ __('rating') }}</label>
                                        <div class="rating d-flex justify-content-start" id="ratingStars">
                                            <span data-value="1">☆</span>
                                            <span data-value="2">☆</span>
                                            <span data-value="3">☆</span>
                                            <span data-value="4">☆</span>
                                            <span data-value="5">☆</span>
                                        </div>
                                        <input type="hidden" name="rating" id="ratingValue" value="0">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>
        $(document).ready(function(){
            const stars = document.querySelectorAll(".rating > span");

            stars.forEach((star, index) => {
                star.addEventListener("click", () => {
                    const ratingValue = index + 1; 
                    resetRating();
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add("checked");
                    }
                    document.querySelector('input[name="rating"]').value = ratingValue;
                    const selectedStarsCount = document.querySelectorAll('.rating > span.checked').length;
                    console.log("Selected Stars Count: " + selectedStarsCount);
                });
            });

            function resetRating() {
                stars.forEach(star => {
                    star.classList.remove("checked");
                });
            }
        });

    </script>                                           
@endpush
