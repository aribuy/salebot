@extends('backend.layouts.master')
@section('title', __('clients'))
@section('content')
	<div class="main-content-wrapper">
		<section class="oftions">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<h3 class="section-title">{{__('add_client') }}</h3>
						<form action="{{ route('clients.store') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="bg-white redious-border p-20 p-sm-30">
								<h6 class="sub-title">{{__('client_information')  }}</h6>
								<div class="row gx-20">
									<div class="col-lg-4">
										<div class="mb-4">
											<label for="first_name"
											       class="form-label">{{__('first_name') }}<span
														class="text-danger">*</span></label>
											<input type="text" class="form-control rounded-2" id="first_name"
											       name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('first_name') }}" required>
											@if ($errors->has('first_name'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('first_name') }}</p>
												</div>
											@endif
										</div>
									</div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label for="last_name"
                                                   class="form-label">{{__('last_name') }}<span
                                                        class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="last_name"
                                                   name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('last_name') }}" required>
                                            @if ($errors->has('last_name'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('last_name') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
									<div class="col-lg-4">
										<div class="mb-4">
											<label for="organisationName"
											       class="form-label">{{__('company_name') }}<span
														class="text-danger">*</span></label>
											<input type="text" class="form-control rounded-2" id="company_name"
											       name="company_name" value="{{ old('company_name') }}" placeholder="{{ __('company_name') }}" required>
											@if ($errors->has('company_name'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('company_name') }}</p>
												</div>
											@endif
										</div>
									</div>
									<div class="col-lg-4">
										@include('backend.common.tel-input', [
											'name' => 'phone_number',
											'value' => old('phone_number'),
											'label' =>  __('phone_number'),
											'id' => 'orgPhoneNumber',
											'country_id_field' => 'phone_country_id',
											'country_id' => old('phone_country_id') ?: (setting('default_country') ?: 19)
										])
									</div>
									<!-- End Phone Number Field -->

									<div class="col-lg-4">
										<div class="mb-4">
											<label for="email"
											       class="form-label">{{__('email_address') }}<span
														class="text-danger">*</span></label>
											<input type="email" class="form-control rounded-2" id="email"
											       name="email" value="{{ old('email') }}"
											       autocomplete="off" placeholder="{{ __('email') }}" required>
											@if ($errors->has('email'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('email') }}</p>
												</div>
											@endif
										</div>
									</div>
									<!-- End Email Address -->

									<div class="col-lg-4">
										<div class="select-type-v2 mb-4 list-space">
											<label for="country" class="form-label">{{__('country') }}<span
														class="text-danger">*</span></label>
											<div class="select-type-v1 list-space">
												<select class="form-select form-select-lg rounded-0 mb-3 with_search"
												        aria-label=".form-select-lg example" name="country_id" required>
													<option value="" selected>{{ __('select_country') }}</option>
													@foreach ($countries as $country)
														<option
																value="{{ $country->id }}">{{__($country->name) }}</option>
													@endforeach
												</select>
												@if ($errors->has('country_id'))
													<div class="nk-block-des text-danger">
														<p>{{ str_replace('id', '', $errors->first('country_id')) }}</p>
													</div>
												@endif
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="mb-4">
											<label for="password" class="form-label">{{ __('password') }}<span
														class="text-danger">*</span></label>
											<input type="password" class="form-control rounded-2" id="password"
											       name="password" placeholder="{{ __('enter_password') }}" required>
											<div class="nk-block-des text-danger">
												<p class="password_error error">{{ $errors->first('password') }}</p>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="mb-4">
											<label for="confirm_password"
											       class="form-label">{{ __('confirm_password') }}<span
														class="text-danger">*</span></label>
											<input type="password" class="form-control rounded-2"
											       id="confirm_password"
											       name="password_confirmation"
											       placeholder="{{ __('re_enter_password') }}" required>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="mb-4">
											<label for="address"
											       class="form-label">{{ __('address_line') }}<span
														class="text-danger">*</span></label>
											<input type="text" class="form-control rounded-2"
											       id="address"
											       name="address"
											       value="{{ old('address') }}"
											       placeholder="{{ __('address') }}" required>
											@if ($errors->has('address'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('address') }}</p>
												</div>
											@endif
										</div>
									</div>
									<div class="col-lg-6 input_file_div">
										<div class="mb-3">
											<label class="form-label mb-1">{{ __('profile_image') }}</label>
											<label for="images"
											       class="file-upload-text"><p></p>
												<span class="file-btn">{{ __('choose_file') }}</span></label>
											<input class="d-none file_picker" type="file" id="images"
											       name="images" accept=".jpg,.png">
											<div class="nk-block-des text-danger">
												<p class="image_error error">{{ $errors->first('images') }}</p>
											</div>
										</div>
										<div class="selected-files d-flex flex-wrap gap-20">
											<div class="selected-files-item">
												<img class="selected-img" src="{{ getFileLink('80x80', []) }}"
												     alt="favicon">
											</div>
										</div>
									</div>

									<div class="col-lg-6 input_file_div">
										<div class="mb-3">
											<label class="form-label mb-1">{{ __('logo') }}</label>
											<label for="logo"
												   class="file-upload-text"><p></p>
												<span class="file-btn">{{ __('choose_file') }}</span></label>
											<input class="d-none file_picker" type="file" id="logo"
												   name="logo" accept=".jpg,.png">
											<div class="nk-block-des text-danger">
												<p class="logo_error error">{{ $errors->first('logo') }}</p>
											</div>
										</div>
										<div class="selected-files d-flex flex-wrap gap-20">
											<div class="selected-files-item">
												<img class="selected-img" src="{{ getFileLink('80x80', []) }}"
													 alt="logo">
											</div>
										</div>
									</div>

									<div class="d-flex justify-content-between align-items-center mt-30">
										<button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
										@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
	@include('backend.common.gallery-modal')
@endsection
@push('js')
	<script src="{{ static_asset('admin/js/countries.js') }}"></script>

@endpush
@push('css_asset')
	<link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@push('js_asset')

@endpush
