@extends('backend.layouts.master')

@section('title')
	{{ __('Imports') }}
@endsection
@section('content')
	<style>
        input#choose_file {
            padding-left: 11px;
            height: 38px;
        }
	</style>
	<div class="container-fluid">
		<div class="row gx-20">
			<div class="col-lg-12">
				<div class="header-top d-flex justify-content-between align-items-center mb-12">
					<h3 class="section-title">{{ __('Imports') }}</h3>
					<div class="oftions-content-right">
						<a href="{{ url()->previous() }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
							<i class="las la-arrow-left"></i>
							<span>{{ __('back') }}</span>
						</a>
					</div>
				</div>
				<form action="{{ route('client.store.Imports') }}"
				      class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
					@csrf
					@method('POST')
					<div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
						<div class="row">
							<div class="col-md-12">
								<div class="card-inner">
									<div class="row g-gs">
										<div class="col-md-3">
											<div class="mb-4">
												<div class="select-type-v2">
													<label for="contact_list_id"
													       class="form-label">{{ __('contact_lists') }}<span
																class="text-danger"></span></label>
													<select id="contact_list_id" name="contact_list_id[]"
													        class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
													        aria-label=".form-select-lg example" multiple="multiple">
														@foreach($lists as $list)
															<option value="{{ $list->id }}">{{ $list->name }}</option>
														@endforeach
													</select>
													<div class="nk-block-des text-danger">
														<p class="error">{{ $errors->first('contact_list_id[]') }}</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="mb-4">
												<div class="select-type-v2">
													<label for="segment_id"
													       class="form-label">{{ __('segments') }}<span
																class="text-danger"></span></label>
													<select id="segment_id" name="segment_id[]"
													        class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
													        aria-label=".form-select-lg example" multiple="multiple">
														@foreach($segments as $segment)
															<option value="{{ $segment->id }}">{{ $segment->title }}</option>
														@endforeach
													</select>
													<div class="nk-block-des text-danger">
														<p class="error">{{ $errors->first('segment_id[]') }}</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<h5>{{ __('n_b') }}</h5>
											<p>{{ __('please_check_this_before_importing_your_file') }}</p>
											<ul class="list list-sm list-success">
												<li>{{ __('uploaded_file_must_be_xlsx_or_csv') }}</li>
												<li>{{ __('the_file_must_contain_phone_number') }}</li>
												<li>
													<a class="import-sample-btn"
													   href="{{ route('client.contacts.export') }}" style="color: red;">
														<span><i class="icon las la-file-download"></i></span>
														<span>{{ __('contacts_import_sample') . ' ' . __('download') }}</span>
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="row g-gs">
										<div class="col-md-6">
											<div class="mb-3">
												<label for="choose_file"
												       class="form-label">{{ __('choose_file') }}</label>
												<input class="form-control" name="file" type="file" id="choose_file"
												       accept=".xlsx, .csv">
											</div>
											@if ($errors && $errors->any())
												@foreach ($errors->all() as $error)
													<div class="invalid-feedback help-block">
														<p>{{ $error }}</p>
													</div>
												@endforeach
											@endif
											<div class="col-md-12 text-right mt-4">
												<div class="mb-3">
													<button type="submit"
													        class="btn sg-btn-primary resubmit">{{ __('submit') }}</button>
													@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
												</div>
											</div>
										</div>
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
	<script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
	</script>
@endpush


                {{-- <form action="{{ route('client.store.Imports') }}"
				      class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
					@csrf
					@method('POST')
					<div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
						<div class="row">
							<div class="col-md-12">
								<div class="card-inner">
									<div class="row g-gs">
										<div class="col-md-3">
											<div class="mb-4">
												<div class="select-type-v2">
													<label for="contact_list_id"
													       class="form-label">{{ __('contact_lists') }}<span
																class="text-danger"></span></label>
													<select id="contact_list_id" name="contact_list_id[]"
													        class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
													        aria-label=".form-select-lg example" multiple="multiple">
														@foreach ($lists as $list)
															<option value="{{ $list->id }}">{{ $list->name }}</option>
														@endforeach
													</select>
													<div class="nk-block-des text-danger">
														<p class="error">{{ $errors->first('contact_list_id[]') }}</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="mb-4">
												<div class="select-type-v2">
													<label for="segment_id"
													       class="form-label">{{ __('segments') }}<span
																class="text-danger"></span></label>
													<select id="segment_id" name="segment_id[]"
													        class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
													        aria-label=".form-select-lg example" multiple="multiple">
														@foreach ($segments as $segment)
															<option value="{{ $segment->id }}">{{ $segment->title }}</option>
														@endforeach
													</select>
													<div class="nk-block-des text-danger">
														<p class="error">{{ $errors->first('segment_id[]') }}</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<h5>{{ __('n_b') }}</h5>
											<p>{{ __('please_check_this_before_importing_your_file') }}</p>
											<ul class="list list-sm list-success">
												<li>{{ __('uploaded_file_must_be_xlsx_or_csv') }}</li>
												<li>{{ __('the_file_must_contain_phone_number') }}</li>
												<li>
													<a class="import-sample-btn"
													   href="{{ route('client.contacts.export') }}" style="color: red;">
														<span><i class="icon las la-file-download"></i></span>
														<span>{{ __('contacts_import_sample') . ' ' . __('download') }}</span>
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="row g-gs">
										<div class="col-md-6">
											<div class="mb-3">
												<label for="choose_file"
												       class="form-label">{{ __('choose_file') }}</label>
												<input class="form-control" name="file" type="file" id="choose_file"
												       accept=".xlsx, .csv">
											</div>
											@if ($errors && $errors->any())
												@foreach ($errors->all() as $error)
													<div class="invalid-feedback help-block">
														<p>{{ $error }}</p>
													</div>
												@endforeach
											@endif
											<div class="col-md-12 text-right mt-4">
												<div class="mb-3">
													<button type="submit"
													        class="btn sg-btn-primary resubmit">{{ __('submit') }}</button>
													@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form> --}}


				 {{-- <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <div class="select-type-v2">
                                                <label for="contact_list_id"
                                                    class="form-label">{{ __('contact_lists') }}<span
                                                        class="text-danger"></span></label>
                                                <select id="contact_list_id" name="contact_list_id[]"
                                                    class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
                                                    aria-label=".form-select-lg example" multiple="multiple">
                                                    @foreach ($lists as $key => $list)
                                                        <option value="{{ $list->id }}">{{ $list->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="nk-block-des text-danger">
                                                    <p class="error">{{ $errors->first('contact_list_id[]') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <div class="select-type-v2">
                                                <label for="segment_id" class="form-label">{{ __('segments') }}<span
                                                        class="text-danger"></span></label>
                                                <select id="segment_id" name="segment_id[]"
                                                    class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
                                                    aria-label=".form-select-lg example" multiple="multiple">
                                                    @foreach ($segments as $key => $segment)
                                                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="nk-block-des text-danger">
                                                    <p class="error">{{ $errors->first('segment_id[]') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}