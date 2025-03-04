@extends('backend.layouts.master')
@section('title', __('payment_methods'))
@push('css_asset')
    <link href="{{ asset('admin/css/countrySelect.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('admin/css/intlTelInput.css') }}">
    <style>
        input.country_selector,
        .country_selector button {
            height: 35px;
            margin: 0;
            padding: 6px 12px;
            border-radius: 2px;
            font-family: inherit;
            font-size: 100%;
            color: inherit;
        }

        input#country_selector,
        input#billing_phone {
            padding-left: 47px !important;
        }
        .iti.iti--allow-dropdown {
    width: 100%;
}
    </style>
@endpush

@section('content')

    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9">
                    <h3 class="section-title">{{ __('payment_methods') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row align-items-center g-20">
                            @if (setting('is_offline_activated'))
                                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-12">
                                    <div class="payment-box client_payment_box" data-method="offline">
                                        <div class="payment-icon">
                                            <img src="{{ static_asset('images/payment-icon/offline.svg') }}" alt="offline">
                                            <span class="title">{{ __('offline') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (setting('is_stripe_activated'))
                                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-12">
                                    <div class="payment-box client_payment_box" data-method="stripe">
                                        <div class="payment-icon">
                                            <img src="{{ static_asset('images/payment-icon/stripe.svg') }}" alt="Stripe">
                                            <span class="title">{{ __('stripe') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (setting('is_paypal_activated'))
                                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-12">
                                    <div class="payment-box client_payment_box" data-method="paypal">
                                        <div class="payment-icon">
                                            <img src="{{ static_asset('images/payment-icon/paypal.svg') }}" alt="paypal">
                                            <span class="title">{{ __('paypal') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (setting('is_paddle_activated'))
                                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-12">
                                    <div class="payment-box client_payment_box" data-method="paddle">
                                        <div class="payment-icon">
                                            <img src="{{ static_asset('images/payment-icon/paddle.svg') }}" alt="paddle">
                                            <span class="title">{{ __('paddle') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3  mt-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <div class="row">
                                                <h6 class="card-title mb-3">{{ __('billing_details') }}</h6>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('name') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('billing_name') is-invalid @enderror"
                                                            name="billing_name" placeholder="{{ __('name') }}"
                                                            required value="{{ Auth::user()->name }}">
                                                        @if ($errors->has('billing_name'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('email') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="email"
                                                            class="form-control @error('billing_email') is-invalid @enderror"
                                                            name="billing_email" placeholder="{{ __('email') }}"
                                                            required value="{{ Auth::user()->email }}">
                                                        @if ($errors->has('billing_email'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_email') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('billing_address') }} <span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control @error('billing_address') is-invalid @enderror" name="billing_address" cols="2"
                                                            rows="2" placeholder="{{ __('billing_address') }}" required>{{ Auth::user()->client->billing_address }}</textarea>
                                                        @if ($errors->has('billing_address'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_address') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('billing_city') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('billing_city') is-invalid @enderror"
                                                            name="billing_city" placeholder="{{ __('billing_city') }}"
                                                            value="{{ Auth::user()->client->billing_city }}" required>
                                                        @if ($errors->has('billing_city'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_city') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('billing_state') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('billing_state') is-invalid @enderror"
                                                            name="billing_state"
                                                            placeholder="{{ __('billing_state') }}"
                                                            value="{{ Auth::user()->client->billing_state }}" required>
                                                        @if ($errors->has('billing_state'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_state') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('billing_zip_code') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('billing_zipcode') is-invalid @enderror"
                                                            name="billing_zipcode"
                                                            placeholder="{{ __('billing_zip_code') }}"
                                                            value="{{ Auth::user()->client->billing_zip_code }}" required>
                                                        @if ($errors->has('billing_zipcode'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_zipcode') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('billing_country') }} <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-item">
                                                            <input id="country_selector" name="billing_country"
                                                                class="form-control" type="text" value="">
                                                            <label for="country_selector" style="display:none;">Select a
                                                                country here</label>
                                                        </div>
                                                        <div class="form-item" style="display:none;">
                                                            <input type="text" id="country_selector_code"
                                                                name="country_selector_code" data-countrycodeinput="1"
                                                                readonly="readonly"
                                                                placeholder="Selected country code will appear here" />
                                                            <label for="country_selector_code">and the selected country
                                                                code
                                                                will be updated here</label>
                                                        </div>

                                                        @if ($errors->has('billing_country'))
                                                            <span
                                                                class="help-block text-danger">{{ $errors->first('billing_country') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label for="billing_phone"
                                                            class="form-label d-block">{{ __('phone') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input id="billing_phone" name="billing_phone"
                                                            class="form-control" type="tel"
                                                            value="{{ Auth::user()->client->billing_phone }}" required >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-4 mb-4 d-none" id="offline_payment">
                        <div class="col-md-6">
                            <h3 class="section-title">{{ __('offline_payment_details') }}</h3>
                            <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                                <form action="{{ route('client.offline.claim') }}" class="form-validate form"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $package->id }}">
                                    {!! setting('offline_payment_instruction') !!}
                                    <div class="d-flex justify-content-end align-items-center mt-30">
                                        <button type="submit"
                                            class="btn btn-block sg-btn-primary">{{ __('claim') }}</button>
                                        @include('backend.common.loading-btn', [
                                            'class' => 'btn sg-btn-primary',
                                        ])
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h3 class="section-title">{{ __('payment_calculation') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <table class="table table-borderless">
                            <tr>
                                <td>{{ __('plan') }}</td>
                                <td class="text-end">
                                    <h6>{{ $package->name }}</h6>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('price') }}</td>
                                <td class="text-end">
                                    <h6>{{ get_price($package->price) }}</h6>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('total') }}</td>
                                <td class="text-end">
                                    <h6>{{ get_price($package->price) }}</h6>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-2 text-center mt-4">

                        <a href="#"
                            class="btn sg-btn-primary d-block payment_btns disabled_a">{{ __('proceed') }}</a>
                        <div class="div_btns d-none">
                            @if (setting('is_offline_activated'))
                                <a href="{{ route('client.stripe.redirect', ['trx_id' => $trx_id, 'payment_type' => 'stripe', 'package_id' => $package->id]) }}"
                                    class="btn sg-btn-primary d-block payment_btns offline_btn">{{ __('claim_offline_subscription') }}</a>
                            @endif
                            @if (setting('is_stripe_activated'))
                                <a href="{{ route('client.stripe.redirect', ['trx_id' => $trx_id, 'payment_type' => 'stripe', 'package_id' => $package->id]) }}"
                                    class="btn sg-btn-primary d-block payment_btns stripe_btn">{{ __('proceed_stripe_payment') }}</a>
                            @endif
                            @if (setting('is_paypal_activated'))
                                <a href="{{ route('client.paypal.redirect', ['trx_id' => $trx_id, 'payment_type' => 'paypal', 'package_id' => $package->id]) }}"
                                    class="btn sg-btn-primary d-block payment_btns paypal_btn">{{ __('proceed_paypal_payment') }}</a>
                            @endif
                            @if (setting('is_paddle_activated'))
                                <a href="{{ route('client.paddle.redirect', ['trx_id' => $trx_id, 'payment_type' => 'paddle', 'package_id' => $package->id]) }}"
                                    class="btn sg-btn-primary d-block payment_btns paddle_btn">{{ __('proceed_paddle_payment') }}</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="{{ asset('admin/js/countrySelect.min.js') }}"></script>
    <script src="{{ asset('admin/js/intlTelInput.js') }}"></script>
    <script>
        $(document).ready(function() {
            var countryCode = '';
            var input = document.querySelector("#billing_phone");
            window.intlTelInput(input, {
                autoHideDialCode: false,
                autoPlaceholder: "on",
                dropdownContainer: document.body,
                formatOnDisplay: true,
                geoIpLookup: function(callback) {
                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                hiddenInput: "full_number",
                initialCountry: "auto",
                nationalMode: false,
                placeholderNumberType: "MOBILE",
                preferredCountries: ['us', 'uk', 'ca'],
                separateDialCode: false,
                utilsScript: "{{ asset('admin/js/utils.js') }}",
            });
        })

        $(document).ready(function() {
            var countrySelector = $("#country_selector");
            countrySelector.countrySelect({
                autoDropdown: true,
                initialCountry: "auto",
                formatOnDisplay: true,
                geoIpLookup: function(callback) {
                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
            });
        });
        $(document).ready(function() {
            $(document).on('click', '.client_payment_box', function() {
                let val = $(this).data('method');
                $('.payment_btns').addClass('d-none');
                $('.div_btns').removeClass('d-none');


                let btn_selector = $('.' + val + '_btn');
                if (val) {
                    if (val == 'offline') {
                        $('#offline_payment').removeClass('d-none');
                        $('.billing_address').addClass('d-none');
                    } else {
                        btn_selector.removeClass('d-none');
                        $('#offline_payment').addClass('d-none');
                        $('.billing_address').removeClass('d-none');
                    }
                }
                $('.client_payment_box').removeClass('active_pg');
                $(this).addClass('active_pg');
            });
        });
    </script>
@endpush
