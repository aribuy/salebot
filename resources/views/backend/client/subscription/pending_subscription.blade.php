@extends('backend.layouts.master')
@section('title', __('my_subscription'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col col-lg-6 col-md-6">
					<div class="bg-white redious-border mb-4 p-20 p-sm-30">
						<div class="row">
							<div class="col-md-12 mb-3 justify-content-center">
								<div class="analytics clr-1 ">
									<div class="analytics-icon">
										<i class="las la-sync"></i>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="analytics clr-1">
									<div class="analytics-content">
										<h4>{{__('offline_payment_pending_title')}}</h4>
										<p class="">{{__('offline_payment_pending_description')}}</p>
									</div>
								</div>
								<div class="text-center">
									<a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-primary gap-2  mt-20 mb-20">
										<span>{{__('refresh_transaction_status')}}</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade" id="department" tabindex="-1" aria-labelledby="department" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content">
				<h6 class="sub-title create_sub_title">{{__('add_new_department') }}</h6>
				<button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
				@include('backend.admin.support.department.create')
			</div>
		</div>
	</div>
	@include('backend.common.delete-script')
@endsection
