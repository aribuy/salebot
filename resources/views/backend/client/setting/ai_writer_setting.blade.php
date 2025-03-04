@extends('backend.layouts.master')
@section('title', __('ai_writter_setting'))
@section('content')
    <div class="row justify-content-md-center">
        <div class="col col-lg-9 col-md-9">
            <h3 class="section-title">{{__('ai_writer_setting') }}</h3>
            <div class="bg-white redious-border p-20 p-sm-30">
                <form action="{{ route('client.ai.writer') }}" class="form-validate form" method="POST">
                    @csrf
                    <input type="hidden" class="is_modal" value="0"/>
                    <div class="col-12">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="secret_key" class="form-label">{{__('secret_key') }} <span
                                        class="text-danger">*</span></label>
                                <a href="https://platform.openai.com/account/api-keys"
                                target="_blank">{{ __('click_here_to_get_the_key') }}</a>
                            </div>
                            <input type="text" class="form-control rounded-2" id="secret_key"
                                name="ai_secret_key" value="{{ isDemoMode() ? '******************' : @Auth::user()->client->open_ai_key  }}" placeholder="{{ __('enter_secret_key') }}" required>
                            <div class="nk-block-des text-danger">
                                <p class="secret_key_error error">{{ $errors->first('secret_key') }}</p>
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
@endsection
