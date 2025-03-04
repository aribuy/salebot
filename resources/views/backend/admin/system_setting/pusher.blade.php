@extends('backend.layouts.master')
@section('title', __('pusher_notification'))
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col col-lg-6 col-md-9">
                <div class="section-top">
                    <h6 class="section-title">{{ __('pusher_notification') }}</h6>
                    <div class=" d-flex gap-20">
                        <button type="button" class="btn sg-btn-primary"
                            id="check_pusher_credentials">{{ __('check_pusher_credentials') }}</button>
                        <button type="button" class="btn sg-btn-primary"
                            id="test_pusher">{{ __('test_pusher_notification') }}</button>
                    </div>
                </div>
                <div class="bg-white redious-border pt-30 p-20 p-sm-30">
                    <form action="{{ route('pusher.notification') }}" method="post" class="form">@csrf
                        <div class="row gx-20">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                        <input type="hidden" name="is_pusher_notification_active"
                                            value="{{ setting('is_pusher_notification_active') == 1 ? 1 : 0 }}">
                                        <label class="form-label"
                                            for="is_pusher_notification_active">{{ __('status') }}</label>
                                        <div class="setting-check">
                                            <input type="checkbox" value="1" id="is_pusher_notification_active"
                                                class="sandbox_mode"
                                                {{ setting('is_pusher_notification_active') == 1 ? 'checked' : '' }}>
                                            <label for="is_pusher_notification_active"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="pusher_app_id" class="form-label">{{ __('app_id') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="pusher_app_id"
                                        name="pusher_app_id" placeholder="{{ __('enter_app_id') }}"
                                        value="{{ isDemoMode() ? '******************' : setting('pusher_app_id') }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="pusher_app_id_error error"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="pusher_app_key" class="form-label">{{ __('app_key') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="pusher_app_key"
                                        name="pusher_app_key" placeholder="{{ __('enter_app_key') }}"
                                        value="{{ isDemoMode() ? '******************' : setting('pusher_app_key') }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="pusher_app_key_error error"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="secret_key" class="form-label">{{ __('secret_key') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="secret_key"
                                        name="pusher_app_secret" placeholder="{{ __('enter_secret_key') }}"
                                        value="{{ isDemoMode() ? '******************' : setting('pusher_app_secret') }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="pusher_app_secret_error error"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="app_cluster" class="form-label">{{ __('app_cluster') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="app_cluster"
                                        name="pusher_app_cluster" placeholder="{{ __('enter_app_cluster') }}"
                                        value="{{ isDemoMode() ? '******************' : setting('pusher_app_cluster') }}">
                                    <div class="nk-block-des text-danger">
                                        <p class="pusher_app_cluster_error error"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-30">
                            <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
                            @include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
                        </div>
                    </form>
                    <span class="text-center d-block">{{ __("if_doesn't_have_an_pusher_account_yet?") }} <a
                            href="https://pusher.com/" target="_blank"
                            class="sg-text-primary">{{ __('click_here') }}</a></span>
                </div>
            </div>
        </div>
    </div>
 
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
        {{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}
        <script src="{{ static_asset('admin/js/custom/pusher.js') }}"></script>
        <script>
          var test_pusher = "{{ route('admin.pusher.test') }}";
          var check_credentials = "{{ route('admin.pusher.check-pusher-credentials') }}";
        </script>
    @endpush
@endsection
