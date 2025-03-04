@extends('backend.layouts.master')
@section('title', __('my_subscription'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col col-lg-6 col-md-6">
					<h3 class="section-title">{{__('my_subscription') }}</h3>
					<div class="bg-white redious-border mb-4 p-20 p-sm-30">
						<div class="row">
							<div class="col-md-12 mb-3">
								<div class="analytics-content mb-1">
									<h4>{{__('hello')}} {{Auth()->user()->first_name }},</h4>
									<p>{{__('my_subscription_welcome_text')}}</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="analytics clr-1">
									<div class="analytics-icon">
										<i class="las la-check-double"></i>
									</div>
									@if($active_subscription)
										<div class="analytics-content">
											<h4>{{ @$active_subscription->plan->name }}</h4>
											<p class="no-line-braek">{{__('next_billing')}}
												: {{ Carbon\Carbon::parse($active_subscription->expire_date)->format('Y-m-d') }}</p>
										</div>
									@else
										<div class="analytics-content">
											<h4>{{__('no_active_plan')}}</h4>
											<p class="no-line-braek">{{__('next_billing')}}: </p>
										</div>
									@endif
								</div>
								<div class="text-center">
									@if($active_subscription->is_recurring)
										<a href="javascript:void(0)"
										   onclick="delete_row('{{ route('client.stop.recurring', $active_subscription->id) }}')"
										   data-toggle="tooltip" class="btn btn-sm btn-secondary gap-2  mt-20 mb-20">
											<span>{{__('stop_recurring')}}</span>
										</a>
									@else
										<a href="javascript:void(0)"
										   onclick="delete_row('{{ route('client.enable.recurring', $active_subscription->id) }}')"
										   data-toggle="tooltip" class="btn btn-sm btn-secondary gap-2  mt-20 mb-20">
											<span>{{__('enable_recurring')}}</span>
										</a>
									@endif
									<a href="{{ route('client.available.plans') }}"
									   class="btn btn-sm btn-primary gap-2  mt-20 mb-20">
										<span>{{__('change_plan')}}</span>
									</a>
									@if($active_subscription->status == 1)
										<a href="javascript:void(0)"
										   onclick="delete_row('{{ route('client.cancel.subscription', $active_subscription->id) }}')"
										   data-toggle="tooltip" class="btn btn-sm btn-danger gap-2 text-white mt-20 mb-20">
											<span>{{__('cancel_now')}}</span>
										</a>
									@endif
								</div>
							</div>
							<div class="col-md-12 default-tab-list default-tab-list-v2 activeItem-bd-md bg-white redious-border p-20 p-sm-30">
								<nav>
									<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
										<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
										        data-bs-target="#nav-home" type="button" role="tab"
										        aria-controls="nav-home" aria-selected="true">
											{{__('plan_details')}}</button>
										<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
										        data-bs-target="#nav-contact" type="button" role="tab"
										        aria-controls="nav-contact" aria-selected="false">
											{{__('subscription_log')}}</button>
									</div>
								</nav>
								<div class="tab-content" id="nav-tabContent">
									<div class="tab-pane fade show active" id="nav-home" role="tabpanel"
									     aria-labelledby="nav-home-tab">
										<table class="table mt-2 mb-2">
											<h4>{{__('plan_details')}}</h4>
											<p>{{__('my_subscription_plan_details')}}</p>
											<tr>
												<td><strong>{{__('plan_name')}}</strong></td>
												<td>{{@$active_subscription->plan->name}}</td>
											</tr>
											<tr>
												<td><strong>{{__('price')}}</strong></td>
												<td>{{@$active_subscription->price}}</td>
											</tr>
											<tr>
												<td><strong>{{__('team_limit')}}</strong></td>
												<td>{{ @$team_remaining }}</td>
											</tr>
											<tr>
												<td><strong>{{__('campaign_limit')}}</strong></td>
												<td>{{ @$active_subscription->campaign_remaining }}</td>
											</tr>
											<tr>
												<td><strong>{{__('contact_limit')}}</strong></td>
												<td>{{@$contact_remaining}}</td>
											</tr>
											<tr>
												<td><strong>{{__('conversation_limit')}}</strong></td>
												<td>{{@$active_subscription->conversation_remaining}}</td>
											</tr>
											<tr>
												<td><strong>{{__('telegram_access')}}</strong></td>
												<td>{{ @$active_subscription->telegram_access == '1' ? __('yes') : __('no') }}</td>
											</tr>
										</table>
									</div>
									<div class="tab-pane fade" id="nav-contact" role="tabpanel"
									     aria-labelledby="nav-contact-tab">
										<table class="table mt-2 mb-2 plan_log">
											<h4>{{__('subscription_log')}}</h4>
											<p>{{__('my_subscription_activity_log')}}</p>
											@php
												$rowNumber = ($log_detail->currentPage() - 1) * $log_detail->perPage() + 1;
											@endphp
											<thead>
											    <th>#</th>
											    <th class="text-center">{{__("date")}}</th>
											    <th class="text-end">{{__("description")}}</th>
											</thead>
											<tbody>
											@foreach($log_detail as $log)
												<tr>
													<td>{{ $rowNumber++ }}</td>
													<td class="text-center">{{$log->created_at->format('d-F-Y')}}<br>{{$log->created_at->format('H:i:s')}}</td>
													<td class="text-end">{{$log->description}}</td>
												</tr>
											@endforeach
											</tbody>
										</table>
										{{ $log_detail->links() }}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	@include('backend.common.delete-script')
@endsection
