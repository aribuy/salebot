<div class="modal fade" id="subscription" tabindex="-1" aria-labelledby="subscription" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<h6 class="sub-title create_sub_title">{{__('add_new_subscription') }}</h6>
			<button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
			<form action="{{route('create.subscription') }}" method="post" class="form" enctype="multipart/form-data">
				@csrf
				<div class="">
					<div class="row gx-20 add-coupon">
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="client_id" class="form-label">{{__('client') }}<span
											class="text-danger">*</span></label>
								<select class="form-select rounded-0 mb-3 with_search"
								        aria-label=".form-select-lg example" id="client_id" name="client_id"
								        style="width: 100%">
									<option value="" selected>{{ __('select_client') }}</option>
									@foreach($clients as $client)
									<option value="{{ $client->id }}">{{ $client->company_name }}</option>
									@endforeach
								</select>
								<div class="nk-block-des text-danger">
									<p class="client_id_error error"></p>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="plan_id" class="form-label">{{__('plan') }}<span
											class="text-danger">*</span></label>
								<select class="form-select rounded-0 mb-3 without_search"
								        aria-label=".form-select-lg example" id="plan_id" name="plan_id"
								        style="width: 100%">
									<option value="" selected>{{ __('select_plan') }}</option>
									@foreach($plans as $plan)
									<option value="{{ $plan->id }}">{{ $plan->name }} - {{ __($plan->billing_period) }}</option>
									@endforeach
								</select>
								<div class="nk-block-des text-danger">
									<p class="plan_id_error error"></p>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="transaction_id" class="form-label">{{ __('transaction_id') }}<span
											class="text-danger">*</span></label>
								<input type="text" class="form-control rounded-2" id="transaction_id" name="transaction_id"
								       placeholder="{{ __('transaction_id') }}">
								<div class="nk-block-des text-danger">
									<p class="transaction_id_error error"></p>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="amount" class="form-label">{{ __('amount') }}<span
											class="text-danger">*</span></label>
								<input type="number" class="form-control rounded-2" id="amount" name="amount"
								       placeholder="{{ __('amount') }}">
								<div class="nk-block-des text-danger">
									<p class="amount_error error"></p>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-end align-items-center mt-30">
							<button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
							@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

