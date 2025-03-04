@extends('backend.layouts.master')
@section('title', __('department'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col col-lg-8 col-md-8">
					<h3 class="section-title">{{__('department') }}</h3>
					<div class="bg-white rounded-20 p-20 p-sm-30">
						<div class="row">
							@can('departments.create')
								<div class="col-lg-12">
									<div class="oftions-content-right mb-12">
										<a href="#" data-bs-toggle="modal" data-bs-target="#department"
										   class="d-flex align-items-center btn sg-btn-primary gap-2">
											<i class="las la-plus"></i>
											<span>{{__('add_new_department') }}</span>
										</a>
									</div>
								</div>
							@endcan

							<div class="col-lg-12">
								<div class="default-list-table table-responsive">
									<table class="table">
										<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">{{__('title') }}</th>
											<th scope="col">{{__('status') }}</th>
											<th class="text-end">{{__('option') }}</th>
										</tr>
										</thead>
										<tbody>
										@foreach($departments as $key => $department)
											<tr>
												<th>{{ ++$key }}</th>
												<td>{{ $department->lang_title }}</td>
												<td>
													<div class="setting-check">
														<input type="checkbox" class="status-change"
														       {{ ($department->status == 1) ? 'checked' : '' }} data-id="{{ $department->id }}"
														       value="department-status/{{$department->id}}"
														       id="customSwitch2-{{ $department->id }}">
														<label for="customSwitch2-{{ $department->id }}"></label>
													</div>
												</td>

												<td class="action-card">
													<ul class="d-flex gap-30 justify-content-end">
														@can('departments.edit')
															<li>
																<a href="{{ route('departments.edit', $department->id) }}" title="{{__('edit')}}"><i
																			class="las la-edit"></i></a></li>
														@endcan
														@can('departments.destroy')
															<li><a href="javascript:void(0)"
															       onclick="delete_row('{{ route('departments.destroy', $department->id) }}')"
															       data-toggle="tooltip"
															       title="{{ __('delete') }}"><i
																			class="las la-trash-alt"></i></a></li>
														@endcan

													</ul>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="pagination_container">
						@if($departments->total() > 0)
							<div class="pagination pt-20">
								<div class="container-fluid">
									<div class="row align-items-center justify-content-between">

										<div class="col-lg-6 col-sm-6">
											<div class="pagination-content-left">
												{{ __('showing') }} {{ $departments->firstItem() }} {{ __('to') }} {{ $departments->lastItem() }} {{ __('of') }} {{ $departments->total() }}
											</div>
										</div>

										<div class="col-lg-6 col-sm-6">
											<div class="pagination-content-right d-sm-flex justify-content-end">
												<nav aria-label="Page navigation example">
													<ul class="pagination">
														{{ $departments->links('vendor.pagination.bootstrap-4') }}
													</ul>
												</nav>
											</div>
										</div>

									</div>
								</div>
							</div>
						@endif
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
