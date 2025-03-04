@extends('backend.layouts.master')
@section('title', __('reply_ticket'))
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('edit_reply') }}</h3>
                <div class="bg-white redious-border p-20 p-sm-30">
                    <form action="{{ route('ticket.reply.update',$reply->id) }}" class="form" method="post">@csrf
                        <input type="hidden" class="is_modal" value="0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="editor-wrapper mb-4">
                                    <label class="form-label mb-1" for="product-update-editor">{{ __('reply') }}</label>
                                    <textarea id="product-update-editor" name="reply">{!! $reply->reply !!}</textarea>
                                    <div class="nk-block-des text-danger">
                                        <p class="reply_error error"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Text Editor -->

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
                                        <img class="selected-img" src="{{ getFileLink('80x80', $reply->images) }}"
                                             alt="favicon">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-30">
                            <button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
                            @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                        </div>
                    </form>
                </div>
                <!-- END Add Reply Tab====== -->
            </div>
        </div>
    </div>
    @include('backend.common.gallery-modal')
@endsection

@push('css_asset')
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@push('js_asset')

@endpush
@push('js')

@endpush
