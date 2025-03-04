@extends('backend.layouts.master')
@section('title', __('send_notification'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <h3 class="section-title">{{ __('send_notification') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('custom-notification.store') }}" method="POST" class="form">@csrf
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" class="is_modal" value="0"/>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title" name="title"
                                               placeholder="{{ __('enter_title') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Coupon Title -->
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="popup_description"
                                               class="form-label">{{ __('description') }}</label>
                                        <textarea class="form-control" id="popup_description"
                                                  name="description"
                                                  placeholder="{{ __('enter_description') }}"></textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="description_error error">{{ $errors->first('lang') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="action_for"
                                               class="form-label">{{ __('action_for') }} </label>
                                        <input type="text" class="form-control rounded-2" id="action_for" name="action_for"
                                               placeholder="{{ __('enter_url') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="action_for_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 input_file_div mb-4">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">{{ __('upload_photo') }}</label>
                                        <label for="images" class="file-upload-text">
                                            <p></p>
                                            <span class="file-btn">{{ __('choose_file') }}</span>
                                        </label>
                                        <input class="d-none file_picker" type="file" id="images"
                                               name="images">
                                        <div class="nk-block-des text-danger">
                                            <p class="image_error error">{{ $errors->first('images') }}</p>
                                        </div>
                                    </div>
                                    <div class="selected-files d-flex flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img class="selected-img" src="{{ getFileLink('80x80', []) }}"
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
    @include('backend.common.gallery-modal')
@endsection
@push('css_asset')
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@push('js_asset')

@endpush
@push('js')

    <script>
        $(document).ready(function () {
            searchInstructor($('#selectInstructor'));
            searchCourse($('#select_course'));
            searchCategory($('#select_category'));
            searchOrganization($('#select_organization'));
            searchUser($('#select_student'));
            searchBlog($('#select_blog'));
            searchSubjects($('#select_subject'));
            searchBook($('#select_book'));

            $(document).on('change', '#action_for', function () {
                let type = $(this).val();
                $('.type_div').addClass('d-none');
                $('.' + type + '_div').removeClass('d-none');
            });
        });
    </script>
@endpush
