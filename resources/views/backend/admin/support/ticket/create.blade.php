@extends('backend.layouts.master')
@section('title', __('create_ticket'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-title">{{ __('add_new_ticket') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('tickets.store') }}" method="POST" class="form">@csrf
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" name="is_modal" class="is_modal" value="0">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="client" class="form-label">{{ __('client') }} <span class="text-danger">*</span></label>
                                        <select id="client" name="client" class="client form-select mb-3 with_search">
                                            <option value="">{{ __('select_client') }}</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->first_name . ' ' . $client->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="nk-block-des text-danger">
                                            <p class="client_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End First Name -->
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="client_staff" class="form-label">{{ __('reported_to') }} <span class="text-danger">*</span></label>
                                        <select id="client_staff" name="client_staff" class="form-select mb-3 with_search">
                                            <option value="">{{ __('select_staff') }}</option>
                                        </select>
                                        <div class="nk-block-des text-danger">
                                            <p class="client_staff_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Last Name -->

                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="subject" class="form-label">{{ __('subject') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" name="subject" id="subject" placeholder="{{ __('enter_subject') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="subject_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Subject -->

                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="department" class="form-label">{{ __('department') }} <span class="text-danger">*</span></label>
                                        <select id="department" name="department_id" class="form-select form-select-lg mb-3 without_search">
                                            <option value="">{{ __('select_department') }}</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="nk-block-des text-danger">
                                            <p class="department_id_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Category -->

                                <div class="col-lg-4">
                                    <div class="select-type-v2 mb-4">
                                        <label for="priority" class="form-label">{{ __('priority') }} <span class="text-danger">*</span></label>
                                        <select id="priority" name="priority" class="form-select form-select-lg mb-3 without_search">
                                            <option value="">{{ __('select_priority') }}</option>
                                            <option value="low">{{ __('low') }}</option>
                                            <option value="medium">{{ __('medium') }}</option>
                                            <option value="high">{{ __('high') }}</option>
                                        </select>
                                        <div class="nk-block-des text-danger">
                                            <p class="priority_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Priority -->
                                <div class="col-lg-4">
                                    <div class="select-type-v2 mb-4">
                                        <label for="status" class="form-label">{{ __('status') }} <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="form-select form-select-lg mb-3 without_search">
                                            <option value="">{{ __('select_status') }}</option>
                                            <option value="pending">{{ __('pending') }}</option>
                                            <option value="answered">{{ __('answered') }}</option>
                                            <option value="hold">{{ __('on_hold') }}</option>
                                            <option value="open">{{ __('open') }}</option>
                                            <option value="close">{{ __('close') }}</option>
                                        </select>
                                        <div class="nk-block-des text-danger">
                                            <p class="status_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Status -->

                                <div class="col-lg-12">
                                    <div class="editor-wrapper">
                                        <label for="product-update-editor" class="form-label">{{ __('description') }}</label>
                                        <textarea class="form-control h-150" name="body" id="product-update-editor" placeholder="{{ __('write_something_here') }}"></textarea>
                                    </div>
                                    <div class="nk-block-des text-danger">
                                        <p class="description_error error"></p>
                                    </div>
                                </div>
                                <!-- End Description -->
                                <div class="col-lg-12 input_file_div">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">{{ __('file') }}</label>
                                        <label for="images"
                                               class="file-upload-text"> <p></p><span class="file-btn">{{__('choose_file') }}</span></label>
                                        <input class="d-none file_picker" type="file" id="images"
                                               name="images" >
                                        <div class="nk-block-des text-danger">
                                            <p class="images_error error">{{ $errors->first('images') }}</p>
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
                                    <button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
                                    @include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
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
            $(document).ready(function() {
            $('#client').change(function() {
                var clientId = $(this).val();
                if(clientId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('client_staff.list') }}",
                        data: { client_id: clientId },
                        success: function(response) {
                            if(response) {
                                $('#client_staff').empty();
                                $.each(response, function(key, value) {
                                    $('#client_staff').append('<option value="'+ key +'">'+ value +'</option>');
                                });
                            } else {
                                $('#client_staff').empty();
                            }
                        }
                    });
                } else {
                    $('#client_staff').empty();
                }
            });
        });
    </script>
@endpush
