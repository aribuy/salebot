@extends('backend.layouts.master')
@section('title', __('edit_contact_list'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-lg-6">
					<h3 class="section-title">{{__('edit_contact_list') }}</h3>
					<form action="{{ route('client.list.update',$contact_list->id) }}" method="post" enctype="multipart/form-data">
						@csrf
						@method('post')
						<input type="hidden" value="{{ $contact_list->id }}" name="id">
						<div class="bg-white redious-border p-20 p-sm-30">
							<div class="row gx-20">
								<div class="col-12 mb-4">
									<label for="name" class="form-label">{{__('name') }}<span
												class="text-danger">*</span></label>
									<input type="text" class="form-control rounded-2" id="name" name="name" value="{{ old('name',  $contact_list->name) }}">
									@if ($errors->has('name'))
										<div class="nk-block-des text-danger">
											<p>{{ $errors->first('name') }}</p>
										</div>
									@endif
								</div>
								<div class="d-flex gap-12 sandbox_mode_div">
									<input type="hidden" name="status" value="{{ $contact_list->status }}">
									<label class="form-label"
									       for="status">{{ __('status') }}</label>
									<div class="setting-check">
										<input type="checkbox" value="1" id="status"
										       class="sandbox_mode" {{ $contact_list->status == 1 ? 'checked' : '' }}>
										<label for="status"></label>
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
	</section>
@endsection
