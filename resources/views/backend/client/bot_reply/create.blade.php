@extends('backend.layouts.master')
@section('title', __('add_new_reply'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('add_new') }}</h3>
                <div class="bg-white redious-border p-20 p-sm-30">
                    <form action="{{ route('client.bot-reply.store') }}" class="form-validate form" method="POST">
                        @csrf
                        <div class="row gx-20">
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <div class="select-type-v2">
                                        <label for="name" class="form-label mb-1">{{ __('name') }}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control mb-3" type="text" name="name" id="name"
                                            placeholder="{{ __('name') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="name_error error">{{ $errors->first('name') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="select-type-v2 mb-4 list-space">
                                    <label for="reply_type" class="form-label">{{ __('reply_type') }}</label>
                                    <div class="select-type-v1 list-space">
                                        <select class="form-select form-select-lg rounded-0 mb-3 with_search"
                                            id="reply_type" aria-label=".form-select-lg example" name="reply_type">
                                            <option value="" selected>{{ __('select_reply_type') }}</option>
                                            <option value="canned_response">{{ __('canned_response') }}</option>
                                            <option value="exact_match">{{ __('exact_match') }}</option>
                                            <option value="contains">{{ __('contains') }}</option>
                                        </select>
                                        @if ($errors->has('reply_type'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ str_replace('id', '', $errors->first('reply_type')) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-center">
                                <div class="col-lg-4 custom-control custom-checkbox" id="reply_using_ai_field">
                                    <div class="mb-4 mt-4">
                                        <label class="custom-control-label  pb-4" for="reply_using_ai">
                                            <input type="checkbox" class="custom-control-input read common-key pb-4"
                                                name="reply_using_ai" value="1" id="reply_using_ai">
                                            <span>{{ __('reply_using_ai') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4" id="keywords_field">
                                <div class="mb-4">
                                    <label for="keywords" class="form-label">{{ __('keywords') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="keywords" name="keywords" rows="5">{{ old('keywords') }}</textarea>
                                    <div class="nk-block-des text-danger">
                                        <p class="keywords_error error">{{ $errors->first('keywords') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4" id="reply_text_field">
                                    <label for="reply_text" class="form-label">{{ __('reply_text') }}  <span
                                            class="text-danger">*</span></label>
                                           
                                    <textarea class="form-control" id="reply_text" name="reply_text">{{ old('reply_text') }}</textarea>
                                    <small class="d-block">
                                        <italic>{{ __('dynamic_variables') }} @{{ name }}, @{{ phone }}</italic>
                                    </small>
                                    <div class="nk-block-des text-danger">
                                        <p class="reply_text_error error">{{ $errors->first('reply_text') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-12 sandbox_mode_div">
                                <input type="hidden" name="status" value="1">
                                <label class="form-label"
                                       for="status">{{ __('status') }}</label>
                                <div class="setting-check">
                                    <input type="checkbox" value="1" id="status"
                                           class="sandbox_mode" checked>
                                    <label for="status"></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-40">
                            <button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
                            @include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-12">
                            <div class="card h-100 border-0">
                                <div class="card-header">
                                    <h5>{{ __('understanding_reply_types') }}</h5>
                                </div>
                                <div class="card-body">
                                    <ol>
                                        <li>1. {{ __('canned_notice') }}</li>
                                        <li>2. {{ __('exact_notice') }}</li>
                                        <li>3. {{ __('contain_notice') }}
                                        </li>
                                                    </ol>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#reply_type').change(function() {
                var selectedReplyType = $(this).val();
                if (selectedReplyType === 'canned_response') {
                    $('#reply_text_field').show();
                    $('#keywords_field').hide();
                    $('#reply_using_ai_field').hide();
                } else {
                    $('#keywords_field').show();
                    $('#reply_using_ai_field').show();
                    $('#reply_text_field').show();
                }
            });

            $('#reply_using_ai').change(function() {
                var isChecked = $(this).prop('checked');
                if (isChecked) {
                    $('#reply_text_field').hide();
                } else {
                    $('#reply_text_field').show();
                }
            });
        });
    </script>
@endpush
