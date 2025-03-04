@extends('backend.layouts.master')
@section('title', __('general_setting'))
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col col-lg-6 col-md-9">
                <h3 class="section-title">{{ __('system_settings') }}</h3>
                <div class="bg-white redious-border pt-30 p-20 p-sm-30">
                    <div class="section-top">
                        <h6>{{ __('preference') }}</h6>
                    </div>
                    <div class="row gx-20">
                        <div class="col-lg-6">
                            <h6 class="mb-3">{{ __('system') }}</h6>
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="HTTPSActivation">{{ __('https_activation') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" data-field_for="maintenance_mode" class="status-change"
                                               id="HTTPSActivation"
                                               value="setting-status-change/https" {{ setting('https') == 1 ? 'checked' : ''}}>
                                        <label for="HTTPSActivation"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="maintenanceModeActivation">
                                        {{ __('maintenance_mode_activation') }}
                                        @if(setting('maintenance_mode') == 1)
                                            <br>
                                            <small class="text-danger warning_text">{{ __('access_your_site') }}
                                                <strong>
                                                    <a class="text-danger" target="_blank"
                                                       href="{{ url(setting('maintenance_secret')) }}">{{ url(setting('maintenance_secret')) }}</a>
                                                </strong>
                                            </small>
                                        @endif
                                    </label>
                                    <div class="setting-check">
                                        <input data-field_for="maintenance_mode" type="checkbox" class="status-change"
                                               id="maintenanceModeActivation"
                                               value="setting-status-change/maintenance_mode" {{ setting('maintenance_mode') == 1 ? 'checked' : ''}}>
                                        <label for="maintenanceModeActivation"></label>
                                    </div>
                                </div>
                            </div>
                            <h6 class="mb-3 mt-30">{{ __('business_related') }}</h6>
                            {{-- <div class="col-lg-12">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="walletSystemActivation">{{ __('wallet_system_activation') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" class="status-change" id="walletSystemActivation"
                                               value="setting-status-change/wallet_system" {{ setting('wallet_system') == 1 ? 'checked' : ''}}>
                                        <label for="walletSystemActivation"></label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="enableEmailConfirmation">{{ __('disable_email_confirmation') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" class="status-change" id="enableEmailConfirmation"
                                               value="setting-status-change/disable_email_confirmation" {{ setting('disable_email_confirmation') == 1 ? 'checked' : ''}}>
                                        <label for="enableEmailConfirmation"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="enableOTPConfirmation">{{ __('disable_otp_verification') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" class="status-change" id="enableOTPConfirmation"
                                               value="setting-status-change/disable_otp_verification" {{ setting('disable_otp_verification') == 1 ? 'checked' : ''}}>
                                        <label for="enableOTPConfirmation"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="maintenance_mode" tabindex="-1" aria-labelledby="editCurrencyLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <h6 class="sub-title create_sub_title">{{__('maintenance_mode_setting') }}</h6>
                <button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <form action="{{ route('setting.status.change') }}" method="POST">
                    @csrf
                    <input type="hidden" class="maintenance_mode" value="1" name="maintenance_mode">
                    <div class="row gx-20">
                        <div class="col-12">
                            <div class="mb-4">
                                <label for="maintenance_secret" class="form-label">{{__('currency_name') }}</label>
                                <input type="text" class="form-control rounded-2 maintenance_secret"
                                       id="maintenance_secret"
                                       placeholder="{{ __('e.g.') }}123" name="maintenance_secret" required>
                                <div class="nk-block-des text-danger">
                                    <p class="maintenance_secret_error error"></p>
                                </div>
                                <p class="text-danger">{!! __('maintenance_mode_text',['url' => url('/your_given_secret_code')]) !!}</p>
                                <p class="mt-2">{{ __('e.g.') }} : <a href="{{ url('/123') }}">{{ url('/123') }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-30">
                        <button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
