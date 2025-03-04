@extends('backend.layouts.master')
@section('title', __('campaigns'))
@section('content')
	<section class="oftions">
		<div class="row">
			<div class="col-lg-12">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex justify-content-between">
								<h3 class="section-title">{{ @$campaign->campaign_name }}</h3>
							</div>
							<?php
								$client 					= Auth::user()->client;
								$activeContactsCount 		= $client->contacts()->active()->count();
								$campaign_contact 			= $campaign->total_contact ?? 0;
								$campaign_contact_percent 	= $campaign_contact != 0 ? ($campaign_contact / $activeContactsCount ) * 100 : 0;
								$total_delivered 			= $campaign->messages->whereIn('status', [\App\Enums\MessageStatusEnum::READ, \App\Enums\MessageStatusEnum::DELIVERED])->count();
								$total_delivered_percent 	= $total_delivered != 0 ? ( $total_delivered / $campaign_contact ) * 100 : 0;
								$total_read 				= $campaign->messages->where('status',\App\Enums\MessageStatusEnum::READ)->count();
								$read_percent 				= $total_read != 0 ? ($total_read / $total_delivered) * 100 : 0;
							?>
							<div class="redious-border mb-40 p-5 p-sm-20 bg-white" style="position: relative;">
                                <span class="@if ($campaign->campaign_type->value == \App\Enums\TypeEnum::WHATSAPP->value) whatsapp-badge @else telegram-badge @endif">{{ $campaign->campaign_name }} - @if ($campaign->campaign_type->value == \App\Enums\TypeEnum::WHATSAPP->value)
		                                {{ __('whatsapp') }}
	                                @else
		                                {{ __('telegram') }}
	                                @endif
                                </span>
								<div class="mt-4"></div>
								<div class="row">
									<div class="col-xxl-3 col-lg-6 col-md-6 mb-20 mb-xxl-0">
										<div class="bg-white redious-border p-20 p-sm-30">
											<div class="analytics clr-1">
												<div class="analytics-icon">
													<i class="las la-bullhorn"></i>
												</div>
												<div class="analytics-content no-line-braek">
													<h5 class="">{{ @$campaign->template->name }}</h5>
													<p>{{ $campaign->created_at->format('d/m/Y') }}</p>
													<div>
														{{ __('templates') }}
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xxl-3 col-lg-6 col-md-6 mb-20 mb-xxl-0">
										<div class="bg-white redious-border p-20 p-sm-30">
											<div class="analytics clr-2">
												<div class="analytics-icon">
													<i class="lar la-user"></i>
												</div>
												<div class="analytics-content">
													<h4>{{ $campaign_contact }}</h4>
													<p>{{ __('contacts') }}</p>
													<div>
														{{ number_format($campaign_contact_percent, 0) }}%
														{{ __('of_your_contacts') }}
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xxl-3 col-lg-6 col-md-6 mb-20 mb-lg-0">
										<div class="bg-white redious-border p-20 p-sm-30">
											<div class="analytics clr-3">
												<div class="analytics-icon">
													<i class="las la-check-circle"></i>
												</div>
												<div class="analytics-content">
													<h4>{{ number_format($total_delivered_percent, 0) }} %</h4>
													<p>{{ __('delivered_to') }}</p>
													<div>
														{{ $total_delivered }} {{ __('of_the') }}
														{{ $campaign_contact }} {{ __('contacts') }}
													</div>
												</div>
											</div>

										</div>
									</div>
									<div class="col-xxl-3 col-lg-6 col-md-6">
										<div class="bg-white redious-border p-20 p-sm-30">
											<div class="analytics clr-4">
												<div class="analytics-icon">
													<i class="las la-sms"></i>
												</div>
												<div class="analytics-content">
													<h4>{{ number_format($read_percent, 0) }}%</h4>
													<p>{{ __('read_by') }}</p>
													<div>
														{{ $total_read }} {{ __('of_the') }}
														{{ $total_delivered }} {{ __('contacts_messaged') }}
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col col-lg-12 col-md-9">
					<div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
						<div class="row ">
							<div class="col-lg-12">
								<form id="filterForm">
									<div class="col-lg-2">
										<div class="mb-3">
											<div class="select-type-v1 list-space">
												<select class="form-select form-select-lg rounded-0 mb-3 without_search filterable"
												        aria-label=".form-select-lg example" id="status"
												        name="status">
													<option value="">{{ __('all') }}</option>
													<option value="delivered">{{ __('delivered') }}</option>
													<option value="read">{{ __('read') }}</option>
													<option value="failed">{{ __('failed') }}</option>
													<option value="scheduled">{{ __('scheduled') }}</option>
													<option value="sent">{{ __('sent') }}</option>
												</select>
												<div class="nk-block-des text-danger">
													<p class="status_error error"></p>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="default-list-table table-responsive yajra-dataTable">
								{{ $dataTable->table() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@push('js')
	{{ $dataTable->scripts() }}
@endpush
@push('js')
	<script>
        $(document).ready(function () {
            const advancedSearchMapping = (attribute) => {
                console.log(attribute);

                $('#dataTableBuilder').on('preXhr.dt', function (e, settings, data) {
                    data[attribute.name] = attribute.value;
                    console.log('Data being sent to server:', data);
                });
            }

            $(document).on('change', '#filterForm select', function () {
                advancedSearchMapping({
                    name: $(this).attr('name'),
                    value: $(this).val(),
                });
            });

            $(document).on('change', '#status', function (event) {
                event.preventDefault();
                console.log(event);
                $('#dataTableBuilder').DataTable().ajax.reload();
            });
        });
        const refreshDataTable = () => {
            $('#dataTableBuilder').DataTable().ajax.reload();
        }
	</script>
@endpush
