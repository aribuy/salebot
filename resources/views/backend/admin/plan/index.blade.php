@extends('backend.layouts.master')
@section('title', __('plans'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="d-flex align-items-center justify-content-between mb-12">
						<h3 class="section-title">{{__('all_plans')}}</h3>
						@can('price_plans.create')
							<a href="{{ route('plans.create') }}"
							   class="d-flex align-items-center btn sg-btn-primary gap-2">
								<i class="las la-plus"></i>
								<span>{{__('create_plan')}}</span>
							</a>
						@endcan
					</div>
					<div class="bg-white redious-border p-20 p-sm-30">
						<div class="row gx-20">
							@foreach( $plans as $key => $plan)
								<div class="col-xl-3 col-lg-6 col-md-6 mb-4">
									<div class="package-default mb-4 mb-xl-0">
										<div class="package-action-bar d-flex align-items-center justify-content-between px-30 py-20">
											@can('price_plans.edit')
												<div class="d-flex gap-12">
													<div class="setting-check">
														<input type="checkbox" class="status-change"
														       {{ ($plan->status == 1) ? 'checked' : '' }} data-id="{{ $plan->id }}"
														       value="package-status/{{ $plan->id}}"
														       id="customSwitch2-{{ $plan->id }}">
														<label for="customSwitch2-{{ $plan->id }}"></label>
													</div>
												</div>
											@endcan

											<ul class="d-flex align-items-center gap-20">
												@can('price_plans.edit')
													<li><a href="{{ route('plans.edit', $plan->id) }}" class="icon"  title="{{ __('edit') }}"><i
																	class="lar la-edit"></i></a></li>
												@endcan
												@can('price_plans.destroy')
													<li><a href="javascript:void(0)"
													       onclick="delete_row('{{ route('plans.destroy', $plan->id) }}')"
													       data-toggle="tooltip"
													       title="{{ __('delete') }}"><i
																	class="las la-trash-alt"></i></a></li>
												@endcan
											</ul>
										</div>

										<div class="package-header py-40 px-30 text-center" style="background-color: {{ $plan->color }};>
											<h2 class="package-title">{{ $plan->name }}</h2>
											<hr style="margin: 12px 0;">
										</div>

										<div class="package-content">
											<h2 class="package-pirce text-center">{{ get_price($plan->price)}}</h2>

											<ul>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('contacts_limit')}}</p>
													<span>{{ $plan->contact_limit }}</span>
												</li>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('campaigns_limit') }}</p>
													<span>{{ $plan->campaigns_limit }}</span>
												</li>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('team_limit')}}</p>
													<span>{{ $plan->team_limit }}</span>
												</li>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('conversation_limit')}}</p>
													<span>{{ $plan->conversation_limit }}</span>
												</li>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('featured') }}</p>
													@if($plan->featured == 1)
														<span>{{__('yes')}}</span>
													@else
														<span>{{__('no')}}</span>
													@endif
												</li>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('telegram_access') }}</p>
													@if($plan->telegram_access == 1)
														<span>{{__('yes')}}</span>
													@else
														<span>{{__('no')}}</span>
													@endif
												</li>
												<li class="d-flex align-items-center justify-content-between py-3 px-30">
													<p>{{__('billing_period')}}</p>
													<span>{{ __($plan->billing_period) }}</span>
												</li>
											</ul>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	@include('backend.common.delete-script')
@endsection

