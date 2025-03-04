<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12">
	<div class="payment-box">
		<div class="payment-icon">
			<img src="{{ static_asset('images/payment-icon/offline.svg') }}" alt="offline">
			<span class="title">{{ __('offline') }}</span>
		</div>
		@can('payment_methods.edit')
			<div class="payment-settings">
				<div class="payment-settings-btn">
					<a href="#" class="btn btn-md sg-btn-outline-primary" data-bs-toggle="modal"
					   data-bs-target="#offline"><i
								class="las la-cog"></i> <span>{{ __('setting') }}</span></a>
				</div>

				<div class="setting-check">
					<input type="checkbox" id="is_offline_activated" value="setting-status-change/is_offline_activated"
					       class="status-change" {{ setting('is_offline_activated') ? 'checked' : '' }}>
					<label for="is_offline_activated"></label>
				</div>
			</div>
		@endcan
	</div>
</div>
<!-- End Payment box -->
<div class="modal fade" id="offline" tabindex="-1" aria-labelledby="paymentMethodLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<h6 class="sub-title">{{ __('offline') }} {{ __('configuration') }}</h6>
			<button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
			<form action="{{ route('payment.gateway') }}" method="post" class="form">@csrf
				<div class="row gx-20">
					<input type="hidden" name="is_modal" class="is_modal" value="0">
					<input type="hidden" name="payment_method" value="offline">
					<div class="col-12">
						<div class="mb-4">
							<label class="form-label">{{ __('offline_payment_instruction') }}</label>
							<textarea type="text" class="form-control rounded-2" name="offline_payment_instruction" id="product-update-editor"
							          placeholder="{{ __('enter_secret_key') }}">{{ old('offline_payment_instruction',setting('offline_payment_instruction')) }}</textarea>
							<div class="nk-block-des text-danger">
								<p class="offline_payment_instruction_error error"></p>
							</div>
						</div>
					</div>
				</div>
				<!-- END Permissions Tab====== -->
				<div class="d-flex justify-content-end align-items-center mt-30">
					<button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
					@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
				</div>
			</form>
		</div>
	</div>
</div>
