@extends('backend.layouts.master')
@section('title', __('general_settings'))
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-xl-8 col-lg-10">
				<h3 class="section-title">{{ __('general_settings') }}</h3>
					<form action="{{ route('client.general.settings.update', $client->id) }}" method="post" enctype="multipart/form-data">
						@csrf
						@method('post')
						<div class="bg-white redious-border p-20 p-sm-30">
							<h6 class="sub-title">{{__('client_information')  }}</h6>
							<div class="row gx-20">
								<div class="col-lg-6">
									<div class="mb-4">
										<label for="organisationName"
										       class="form-label">{{__('company_name') }}<span
													class="text-danger">*</span></label>
										<input type="text" class="form-control rounded-2" id="company_name"
										       name="company_name" value="{{ old('company_name',  $client->company_name) }}" placeholder="{{ __('company_name') }}">
										@if ($errors->has('company_name'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('company_name') }}</p>
											</div>
										@endif
									</div>
								</div>
								<div class="col-lg-6">
									<div class="select-type-v2 mb-4 list-space">
										<label for="country" class="form-label">{{__('country') }}<span
													class="text-danger">*</span></label>
										<div class="select-type-v1 list-space">
											<select class="form-select form-select-lg rounded-0 mb-3 with_search"
											        aria-label=".form-select-lg example" name="country_id">
												<option value="" selected>{{ __('select_country') }}</option>
												@foreach ($countries as $country)
													<option
															value="{{ $country->id }}" {{ $country->id == old('country_id', $client->country_id) ? 'selected' : '' }}>{{__($country->name) }}</option>
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
								<div class="col-lg-6">
									<div class="mb-4">
										<label for="address"
										       class="form-label">{{ __('address_line') }}</label>
										<input type="text" class="form-control rounded-2"
										       id="address"
										       name="address"
										       value="{{ old('email',$client->address) }}"
										       placeholder="{{ __('address') }}">
										@if ($errors->has('address'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('address') }}</p>
											</div>
										@endif
									</div>
								</div>
								<div class="col-lg-6 mb-4">
									<label for="time_zone" class="form-label">{{ __('time_zone') }}</label>
									<select class="form-select form-select-lg mb-3 with_search" name="time_zone"
									        id="time_zone">
										@foreach ($time_zones as $time_zone)
											<option value="{{ $time_zone->timezone }}"
													{{ $time_zone->timezone == $client->timezone  ? 'selected' : '' }}>
												{{ $time_zone->gmt_offset > 0 ? "(UTC +$time_zone->gmt_offset)" . ' ' . $time_zone->timezone : "(UTC $time_zone->gmt_offset)" .' '. $time_zone->timezone}}
											</option>
										@endforeach
									</select>
									<div class="nk-block-des text-danger">
										<p class="time_zone_error error">{{ $errors->first('time_zone') }}</p>
									</div>
								</div>
								<div class="col-lg-6 input_file_div">
									<div class="mb-3">
										<label class="form-label mb-1">{{ __('logo') }}</label>
										<label for="logo"
										       class="file-upload-text"><p></p><span class="file-btn">{{ __('choose_file') }}</span></label>
										<input class="d-none file_picker" type="file" id="logo"
										       name="logo">
										<div class="nk-block-des text-danger">
											<p class="logo_error error">{{ $errors->first('logo') }}</p>
										</div>
									</div>
									<div class="selected-files d-flex flex-wrap gap-20">
										<div class="selected-files-item">
											<img class="selected-img" src="{{ getFileLink('80x80', $client->logo) }}"
											     alt="favicon">
										</div>
									</div>
								</div>
								<div class="d-flex justify-content-end align-items-center mt-30">
									<button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
									@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
								</div>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script src="{{ static_asset('admin/js/countries.js') }}"></script>

@endpush
@push('css_asset')
	<link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush


