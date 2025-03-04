@extends('backend.layouts.master')
@section('title', __('billing_details'))
@push('css_asset')
	<link href="{{ static_asset('admin/css/countrySelect.min.css') }}" rel="stylesheet" />
	<link rel="stylesheet" href="{{ static_asset('admin/css/intlTelInput.css') }}">
	<link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
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
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-xl-8 col-lg-10">
				<h3 class="section-title">{{ __('billing_details') }}</h3>
				<form action="{{route('client.billing.details.store', $client->id)}}" method="post" enctype="multipart/form-data">
					@csrf
					@method('post')
					<div class="bg-white redious-border p-20 p-sm-30">
						<div class="row">
							<div class="col-lg-12 mb-3  mt-3">
								<div class="row">
									<div class="col-md-4 col-xl-4">
										<div class="mb-3">
											<label class="form-label">{{ __('name') }} <span
														class="text-danger">*</span></label>
											<input type="text"
											       class="form-control @error('billing_name') is-invalid @enderror"
											       name="billing_name" placeholder="{{ __('name') }}"
											       required value="{{ $client->name }}">
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
											       name="billing_address" placeholder="{{ __('billing_address') }}"
											       value="{{ $client->billing_address }}" required>
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
											       value="{{ $client->billing_city }}" required>
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
											       value="{{ $client->billing_state }}" required>
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
											       value="{{ $client->billing_zip_code }}" required>
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
												       placeholder="Selected country code will appear here"/>
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
											       value="{{ $client->billing_phone }}" required>
										</div>
									</div>
									<div class="d-flex justify-content-end align-items-center mt-30">
										<button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
										@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script src="{{ static_asset('admin/js/countrySelect.min.js') }}"></script>
	<script src="{{ static_asset('admin/js/intlTelInput.js') }}"></script>
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
                utilsScript: "{{ static_asset('admin/js/utils.js') }}",
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
        </script>
@endpush


