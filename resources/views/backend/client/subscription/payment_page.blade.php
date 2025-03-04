@extends('backend.layouts.master')
@section('title', __('payment_methods'))
@push('css_asset')
    <link href="{{ static_asset('admin/css/countrySelect.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ static_asset('admin/css/intlTelInput.css') }}">
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

        /* Loading effect for the submit button */
        .loading_button {
            position: relative; /* For positioning spinner */
            cursor: not-allowed !important; /* Prevent user interaction */
        }

        .loading_button::after {
            content: ""; /* Placeholder for spinner */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid #f3f3f3; /* Light gray border */
            border-top: 2px solid #354ABF; /* Spinner color */
            border-radius: 50%;
            width: 15px; /* Size of spinner */
            height: 15px;
            animation: spin 1s linear infinite; /* Animation for spinning */
        }

        @keyframes spin {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            to {
                transform: translate(-50%, -50%) rotate(360deg); /* Full spin */
            }
        }
    </style>
@endpush

@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <form  
            @if ($package->is_free == 1 && $package->price == 0)
            action="{{ route('client.upgrade-plan.free', ['trx_id' => $trx_id, 'payment_type' => 'free', 'package_id' => $package->id]) }}"
            @else
            action=""
            @endif   
            class="payment_form" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <input type="hidden" name="plan_id" value="{{ $package->id }}">
                <div class="row">
                    <div class="col-lg-9">
                        <h3 class="section-title">{{ __('billing_details') }}</h3>
                        <div class="bg-white redious-border p-20 p-sm-20 pt-sm-20 mb-2">
                            <div class="row billing_details">
                                <div class="card-body">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('name') }} <span
                                                                class="text-danger">*</span></label>
                                                    <input type="text"
                                                           class="form-control @error('billing_name') is-invalid @enderror"
                                                           name="billing_name" placeholder="{{ __('name') }}"
                                                           required value="{{ Auth::user()->client->name }}">
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
                                                    <input type="text"
                                                           class="form-control @error('billing_address') is-invalid @enderror"
                                                           name="billing_address"
                                                           value="{{ Auth::user()->client->billing_address }}"
                                                           placeholder="{{ __('billing_address') }}"
                                                           required>
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
                                                           name="billing_city"
                                                           placeholder="{{ __('billing_city') }}"
                                                           value="{{ Auth::user()->client->billing_city }}"
                                                           required>
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
                                                           value="{{ Auth::user()->client->billing_state }}"
                                                           required>
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
                                                           value="{{ Auth::user()->client->billing_zip_code }}"
                                                           required>
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
                                                        <label for="country_selector" style="display:none;">Select
                                                            a
                                                            country here</label>
                                                    </div>
                                                    <div class="form-item" style="display:none;">
                                                        <input type="text" id="country_selector_code"
                                                               name="country_selector_code"
                                                               data-countrycodeinput="1"
                                                               readonly="readonly"
                                                               placeholder="Selected country code will appear here"/>
                                                        <label for="country_selector_code">and the selected
                                                            country
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
                                                           value="{{ Auth::user()->client->billing_phone }}"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($package->is_free==0)
                            <h3 class="section-title">{{ __('payment_methods') }}</h3>
                            <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                                <div class="row align-items-center g-20">
                                    @if (setting('is_offline_activated'))
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                                            <div class="payment-box client_payment_box" data-method="offline">
                                                <div class="payment-icon">
                                                    <img src="{{ static_asset('images/payment-icon/offline.svg') }}"
                                                         alt="offline">
                                                    <span class="title">{{ __('offline') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (setting('is_stripe_activated'))
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                                            <div class="payment-box client_payment_box" data-method="stripe">
                                                <div class="payment-icon">
                                                    <img src="{{ static_asset('images/payment-icon/stripe.svg') }}"
                                                         alt="Stripe">
                                                    <span class="title">{{ __('stripe') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (setting('is_paypal_activated'))
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                                            <div class="payment-box client_payment_box" data-method="paypal">
                                                <div class="payment-icon">
                                                    <img src="{{ static_asset('images/payment-icon/paypal.svg') }}"
                                                         alt="paypal">
                                                    <span class="title">{{ __('paypal') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (setting('is_paddle_activated'))
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                                            <div class="payment-box client_payment_box" data-method="paddle">
                                                <div class="payment-icon">
                                                    <img src="{{ static_asset('images/payment-icon/paddle.svg') }}"
                                                         alt="paddle">
                                                    <span class="title">{{ __('paddle') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (setting('is_razor_pay_activated'))
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                                            <div class="payment-box client_payment_box" data-method="razor_pay">
                                                <div class="payment-icon">
                                                    <img src="{{ static_asset('images/payment-icon/razor_pay.svg') }}"
                                                         alt="paddle">
                                                    <span class="title">{{ __('razor_pay') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (setting('is_mercadopago_activated'))
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                                            <div class="payment-box client_payment_box" data-method="mercadopago">
                                                <div class="payment-icon">
                                                    <img src="{{ static_asset('images/payment-icon/mercadopago.svg') }}"
                                                         alt="mercadopago">
                                                    <span class="title">{{ __('mercadopago') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
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
                            @if ($package->is_free==1)
                                <button type="submit"
                                        class="w-100 btn sg-btn-primary d-block payment_btns free_btn">{{ __('proceed') }}</button>
                            @else
                                <a href="#"
                                   class="btn sg-btn-primary d-block payment_btns disabled_a">{{ __('proceed') }}</a>
                            @endif

                            <div class="div_btns d-none">
                                @if (setting('is_offline_activated'))
                                    <button type="submit"
                                            class="btn sg-btn-primary d-block payment_btns offline_btn">{{ __('claim_offline_subscription') }}</button>
                                @endif
                                @if (setting('is_stripe_activated'))
                                    <button type="submit"
                                            class="w-100 btn sg-btn-primary d-block payment_btns stripe_btn">{{ __('proceed_stripe_payment') }}</button>
                                @endif
                                @if (setting('is_paypal_activated'))
                                    <button type="submit"
                                            class="w-100 btn sg-btn-primary d-block payment_btns paypal_btn">{{ __('proceed_paypal_payment') }}</button>
                                @endif
                                @if (setting('is_paddle_activated'))
                                    <button type="submit"
                                            class="w-100 btn sg-btn-primary d-block payment_btns paddle_btn">{{ __('proceed_paddle_payment') }}</button>
                                @endif
                                @if (setting('is_razor_pay_activated'))
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary w-100'])
                                    <button type="submit"
                                            class="w-100 btn sg-btn-primary d-block payment_btns razor_pay_btn">{{ __('proceed_razor_pay_payment') }}</button>
                                @endif
                                @if (setting('is_mercadopago_activated'))
                                    <button type="submit" class="w-100 btn sg-btn-primary d-block payment_btns mercadopago_btn">{{ __('proceed_mercadopago_payment') }}</button>
                                @endif

                            </div>

                        </div>


                    </div>
                </div>
            </form>
            <div class="row justify-content-center mt-4 mb-4 d-none" id="offline_payment">
                <div class="col-md-6">
                    <h3 class="section-title">{{ __('offline_payment_details') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <form action="{{ route('client.offline.claim') }}" class="form-validate offline_form"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $package->id }}">
                            {!! setting('offline_payment_instruction') !!}
                            <div class="d-flex justify-content-end align-items-center mt-30">
                                <button type="submit" class="btn btn-block sg-btn-primary">{{ __('claim') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="{{ static_asset('admin/js/countrySelect.min.js') }}"></script>
    <script src="{{ static_asset('admin/js/intlTelInput.js') }}"></script>
    @if(setting('is_razorpay_activated'))
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
    <script>
        $(document).ready(function () {
            var countryCode = '';
            var input = document.querySelector("#billing_phone");
            window.intlTelInput(input, {
                autoHideDialCode: false,
                autoPlaceholder: "on",
                dropdownContainer: document.body,
                formatOnDisplay: true,
                geoIpLookup: function (callback) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always(function (resp) {
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
                utilsScript: "{{ static_asset('admin/js/utils.js') }}",
            });

            var countrySelector = $("#country_selector");
            countrySelector.countrySelect({
                autoDropdown: true,
                initialCountry: "auto",
                formatOnDisplay: true,
                geoIpLookup: function (callback) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
            });

            
            $(document).on('click', '.client_payment_box', function () {
                let val = $(this).data('method');
                let url = '';
                if (val == 'stripe') {
                    url = '{{ route('client.stripe.redirect') }}'+'?trx_id={{ $trx_id }}'+''+'&payment_type=stripe';
                } else if (val == 'paypal') {
                    url = '{{ route('client.paypal.redirect') }}'+'?trx_id={{ $trx_id }}'+''+'&payment_type=paypal';
                } else if (val == 'paddle') {
                    url = '{{ route('client.paddle.redirect') }}'+'?trx_id={{ $trx_id }}'+''+'&payment_type=paddle';
                } else if (val == 'mercadopago') {
                    url = '{{ route('client.mercadopago.redirect') }}'+'?trx_id={{ $trx_id }}'+''+'&payment_type=mercadopago';
                }

                $('.payment_form').attr('action', url);
                $('.payment_btns').addClass('d-none');
                $('.div_btns').removeClass('d-none');
                let btn_selector = $('.' + val + '_btn');
                if (val) {
                    if (val == 'offline') {
                        $('#offline_payment').removeClass('d-none');
                    } else {
                        btn_selector.removeClass('d-none');
                        $('#offline_payment').addClass('d-none');
                    }
                }
                $('.client_payment_box').removeClass('active_pg');
                $(this).addClass('active_pg');
            });

            $(document).on('click', '.razor_pay_btn', function (e) {
                $(this).addClass('d-none');
                $('.loading_button').removeClass('d-none');
                $.ajax({
                    url: "{{ route('client.razor.pay.redirect') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        payment_type: 'razor_pay',
                        plan_id: '{{ $package->id }}',
                        trx_id: '{{ $trx_id }}',
                        billing_name: $('input[name="billing_name"]').val(),
                        billing_email: $('input[name="billing_email"]').val(),
                        billing_address: $('input[name="billing_address"]').val(),
                        billing_city: $('input[name="billing_city"]').val(),
                        billing_state: $('input[name="billing_state"]').val(),
                        billing_zipcode: $('input[name="billing_zipcode"]').val(),
                        billing_country: $('input[name="billing_country"]').val(),
                        billing_phone: $('input[name="billing_phone"]').val(),
                    },
                    success: function (data) {
                        $(this).removeClass('d-none');
                        $('.loading_button').addClass('d-none');
                        if (data.success) {
                            var rzp1 = new Razorpay(data);
                            rzp1.open();
                            e.preventDefault();
                        } else {
                            toastr.error(data.error);
                        }
                    }
                });
            });
        })


        $(document).ready(function () {
            $.fn.serializeFormJSON = function () {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function () {
                    if (o[this.name] !== undefined) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                return o;
            };

            // Form submission handler for the offline form
            $('form.offline_form').submit(function (e) {
                e.preventDefault();
                var button = $(this).find('button[type="submit"]');
                button.addClass('loading_button');

                var formData = $(this).serializeFormJSON();

                formData['billing_name'] = $('input[name="billing_name"]').val();
                formData['billing_email'] = $('input[name="billing_email"]').val();
                formData['billing_address'] = $('input[name="billing_address"]').val();
                formData['billing_city'] = $('input[name="billing_city"]').val();
                formData['billing_state'] = $('input[name="billing_state"]').val();
                formData['billing_zipcode'] = $('input[name="billing_zipcode"]').val();
                formData['billing_country'] = $('input[name="billing_country"]').val();
                formData['billing_phone'] = $('input[name="billing_phone"]').val();

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    beforeSend: function () {
                        $('.loading-btn').addClass('loading');
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.status === true) {

                            window.location.href = response.redirect_to;
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    },
                    complete: function () {
                        button.removeClass('loading_button');
                        $('.loading-btn').removeClass('loading');
                    }
                });
            });
        });
    </script>
@endpush
