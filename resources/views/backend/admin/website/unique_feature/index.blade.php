@extends('backend.layouts.master')
@section('title', __('unique_feature'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{ __('unique_feature') }}</h3>
                        @can('unique_feature.create')
                        <div class="oftions-content-right mb-12">
                            @if (active_theme() !=='darkbot')  
                            <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary gap-2" id="addImageButton">
                                @if(setting('unique_feature_image'))
                                    <span>{{ __('update_image') }}</span>
                                @else
                                    <i class="las la-plus"></i>
                                    <span>{{ __('add_image') }}</span>
                                @endif  
                            </a>
                            @endif
                            <a href="{{ route('unique-feature.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{__('add_unique_feature') }}</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                    <div class="row">
                        <div class="col-lg-8 default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                            <div class="default-list-table table-responsive yajra-dataTable">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                        @if (active_theme() !=='darkbot')  
                        <div class="col-lg-4">
                            <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                                <form action="{{ route('unique-feature.image') }}" method="POST" enctype="multipart/form-data"  id="addImageForm" style="display: none;">
                                    @csrf
                                    <div class="col-lg-12 input_file_div">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{__('image') }}</label>
                                            <label for="unique_feature_image" class="file-upload-text">
                                                <p></p>
                                                <span class="file-btn">{{__('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="unique_feature_image"
                                                   name="unique_feature_image" accept=".jpg,.png">
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img" src="{{  getFileLink('80x80',setting('unique_feature_image')) }}"
                                                     alt="favicon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
                                        @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                    </div>
                                </form>
                                <div class="col-lg-12 input_file_div" id="ImagePreview">
                                    <div class="selected-files d-flex align-items-center justify-content-center flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img src="{{  getFileLink('80x80',setting('unique_feature_image')) }}"
                                                    alt="favicon">
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block-des text-danger">
                                    <p class="unique_feature_image_error error">{{ $errors->first('unique_feature_image') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.common.delete-script')
@endsection
@push('js')
    {{ $dataTable->scripts() }}
    <script>
        $(document).ready(function() {
            $('#addImageButton').on('click', function() {
                $('#addImageForm').toggle();
                $('#ImagePreview').toggle();
            });
        });
    </script>
@endpush
