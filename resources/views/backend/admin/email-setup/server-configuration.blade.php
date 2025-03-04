@extends('backend.layouts.master')
@section('title', __('email_setting'))
@section('content')
	<div class="main-content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="section-title">{{__('email_settings') }}</h3>
					<div class="bg-white redious-border pt-30 p-40">
						<div class="section-top">
							<h6>{{__('server_configuration') }}</h6>
							<div class=" d-flex gap-20">
								<button type="button" class="btn sg-btn-primary" data-bs-toggle="modal"
								        data-bs-target="#testMail">{{__('test_mail') }}</button>
							</div>
						</div>
						<form action="{{ route('email.server-configuration.update') }}" method="post"
						      enctype="multipart/form-data">
							@csrf
							@method('put')
							<div class="row gx-20">
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="SMTPServer"
										       class="form-label">{{__('smtp_server_address') }}<span
													class="text-danger">*</span></label>
										<input type="text" class="form-control rounded-2" id="SMTPServer"
										       name="smtp_server_address"
										       value="{{ isDemoMode() ? '******************' : old('smtp_server_address',env('MAIL_HOST')) }}">
										@if($errors->has('smtp_server_address'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('smtp_server_address') }}</p>
											</div>
										@endif
									</div>
								</div>
								<!-- End SMTP Server Address -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="SMTPUser" class="form-label">{{__('smtp_username') }}<span
													class="text-danger">*</span></label>
										<input type="text" class="form-control rounded-2" id="SMTPUser"
										       placeholder="{{__('smtp_username')}}" name="smtp_user_name"
										       value="{{ isDemoMode() ? '******************' : stringMasking(old('smtp_user_name',env('MAIL_USERNAME')),'*',3,-3) }}">
										@if($errors->has('smtp_user_name'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('smtp_user_name') }}</p>
											</div>
										@endif
									</div>
								</div>
								<!-- End SMTP Username -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="SMTPPassword" class="form-label">{{__('smtp_password') }}<span
													class="text-danger">*</span></label>
										<input type="password" class="form-control rounded-2" id="SMTPPassword"
										       placeholder="********" name="smtp_password"
										       value="{{ isDemoMode() ? '******************' : stringMasking(old('smtp_password',env('MAIL_PASSWORD')),'*',0) }}">
										@if($errors->has('smtp_password'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('smtp_password') }}</p>
											</div>
										@endif
									</div>
								</div>
								<!-- End SMTP Password -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="SMTPPort" class="form-label">{{__('smtp_port') }}<span
													class="text-danger">*</span></label>
										<input type="number" class="form-control rounded-2" id="SMTPPort"
										       placeholder="465/587/25" name="smtp_mail_port"
										       value="{{ old('smtp_mail_port',env('MAIL_PORT')) }}">
										@if($errors->has('smtp_mail_port'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('smtp_mail_port') }}</p>
											</div>
										@endif
									</div>
								</div>
								<!-- End SMTP Port -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="encryption" class="form-label">{{__('encryption_type') }}<span
													class="text-danger">*</span></label>
										<div class="select-type-v2">
											<select id="encryption"
											        class="form-select form-select-lg mb-3 without_search"
											        aria-label=".form-select-lg example" name="smtp_encryption_type">
												<option value="">{{ __('select_encryption_type') }}</option>
												<option
														{{ env('MAIL_ENCRYPTION') == 'ssl' ? "selected" : "" }} value="ssl">{{__('SSL')}}</option>
												<option
														{{ env('MAIL_ENCRYPTION') == 'tls' ? "selected" : "" }} value="tls">{{__('TLS')}}</option>
											</select>
											@if($errors->has('smtp_encryption_type'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('smtp_encryption_type') }}</p>
												</div>
											@endif
										</div>
									</div>
								</div>
								<!-- End Encryption Type -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="MailFName" class="form-label">{{__('mail_from_name') }}<span
													class="text-danger">*</span></label>
										<input type="text" class="form-control rounded-2" id="MailFName"
										       placeholder="{{__('mail_from_name') }}"
										       name="smtp_mail_from_name"
										       value="{{ isDemoMode() ? '******************' : old('smtp_mail_from_name',env('MAIL_FROM_NAME')) }}">
										@if($errors->has('smtp_mail_from_name'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('smtp_mail_from_name') }}</p>
											</div>
										@endif
									</div>
								</div>
								<!-- End Mail From Name -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="emailAddress"
										       class="form-label">{{__('mail_from_address')  }}<span
													class="text-danger">*</span></label>
										<input type="email" class="form-control rounded-2" id="emailAddress"
										       name="mail_from_address"
										       value="{{ isDemoMode() ? '******************' : stringMasking(old('mail_from_address',env('MAIL_FROM_ADDRESS')),'*',3,-3) }}">
										@if($errors->has('mail_from_address'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('mail_from_address') }}</p>
											</div>
										@endif
									</div>
								</div>


								<div class="col-lg-4">
									<div class="mb-4">
										<label for="email-footer"
											   class="form-label">{{__('mail_footer_text')  }}<span
													class="text-danger">*</span></label>
										<input type="text" class="form-control rounded-2" id="email-footer"
											   name="mail_footer_text"
											   value="{{old('mail_footer_text',setting('mail_footer_text')) }}">
										@if($errors->has('mail_footer_text'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('mail_footer_text') }}</p>
											</div>
										@endif
									</div>
								</div>
								<!-- End Replay To -->

								<div class="col-lg-4">
									<div class="mb-4">
										<label for="mail_signature"
										       class="form-label">{{__('email_signature')  }}<span
													class="text-danger">*</span></label>
										<input type="text" class="form-control rounded-2" id="mail_signature"
										       name="mail_signature"
										       value="{{old('mail_signature',setting('mail_signature')) }}">
										@if($errors->has('mail_signature'))
											<div class="nk-block-des text-danger">
												<p>{{ $errors->first('mail_signature') }}</p>
											</div>
										@endif
									</div>
								</div>
							</div>
							@can('email.server_configuration.edit')
								<div class="d-flex justify-content-between align-items-center mt-30">
									<button type="submit" class="btn sg-btn-primary">{{__('update') }}</button>
								</div>
							@endcan
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- END Main Content Wrapper -->
	<!-- Modal For Test Mail======================== -->
	<div class="modal fade" id="testMail" tabindex="-1" aria-labelledby="testMailLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<h6 class="fs-4">{{__('Send Test Mail') }}</h6>
				<button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

				<form action="{{ route('email.test') }}" method="post">
					@csrf
					<div class="row gx-20">
						<div class="col-12">
							<div class="mt-5">
								<label for="testMail" class="form-label bold">{{__('send_to') }}</label>
								<input type="email" class="form-control rounded-2" id="testMail"
								       placeholder="example@email.com" name="send_to">
								@if($errors->has('send_to'))
									<div class="nk-block-des text-danger">
										<p>{{ $errors->first('send_to') }}</p>
									</div>
								@endif
							</div>
						</div>
						<!-- End Send To -->

					</div>
					<!-- END Permissions Tab====== -->
					<div class="d-flex justify-content-end align-items-center mt-30">
						<button type="submit" class="btn sg-btn-primary">{{__('send') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- END Modal For Test Mail======================== -->

@endsection
