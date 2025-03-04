@extends('backend.layouts.master')
@section('title', __('telegram_overview'))
@push('css')
	<link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="section-title">
						<h4>{{__('telegram_overview')}}</h4>
					</div>

					<div class="row">
						<div class="col-xxl-3 col-xl-6 col-md-6">
							<div class="bg-white redious-border mb-4 p-20 p-sm-30">
								<div class="analytics clr-1">
									<div class="analytics-icon">
										<i class="las la-address-book"></i>
									</div>

									<div class="analytics-content">
										<h4>{{ $totalGroups }}</h4>
										<p>{{__('total_group')}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xxl-3 col-xl-6 col-md-6">
							<div class="bg-white redious-border mb-4 p-20 p-sm-30">
								<div class="analytics clr-1">
									<div class="analytics-icon">
										<i class="las la-address-book"></i>
									</div>

									<div class="analytics-content">
										<h4>{{ $totalsubscribers }}</h4>
										<p>{{__('total_subscribers')}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xxl-3 col-xl-6 col-md-6">
							<div class="bg-white redious-border mb-4 p-20 p-sm-30">
								<div class="analytics clr-2">
									<div class="analytics-icon">
										<i class="las la-chalkboard-teacher"></i>
									</div>
									<div class="analytics-content">
										<h4>{{ number_format($activePercentage) }}%</h4>
										<p>{{__('active_subscribers')}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xxl-3 col-xl-6 col-md-6">
							<div class="bg-white redious-border mb-4 p-20 p-sm-30">
								<div class="analytics clr-4">
									<div class="analytics-icon">
										<i class="las la-ban"></i>
									</div> 
									<div class="analytics-content">
										
										<h4>{{ $blacklistCount }}</h4>
										<p>{{__('blacklist')}}</p>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<div class="bg-white redious-border mb-4 pt-20 p-30">
								<div class="section-top">
									<h4>{{ __('audience_growth') }}</h4>
								</div>
								<div class="statistics-report">
									<div class="row">
									</div>
								</div>

								<div class="statistics-report-chart">
									<canvas id="audience_growth"></canvas>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<input type="hidden" id="chart_data" value="{{ json_encode($charts) }}">
@endsection
@push('js')
	<script src="{{ static_asset('admin/js/chart.min.js') }}"></script>
@endpush
@push('js')
	<script src="{{ static_asset('admin\js\custom\dashboard\telegram_overview_chart.js') }}"></script>
@endpush
