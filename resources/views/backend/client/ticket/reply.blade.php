@extends('backend.layouts.master')
@section('title', __('reply_ticket'))
@section('content')
	<form action="{{ route('client.ticket.reply') }}" class="form" method="POST">@csrf
		<div class="container-fluid mb-5">
			<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
			<input type="hidden" name="is_modal" class="is_modal" value="0">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="section-title">#{{ $ticket->ticket_id }} - {{ $ticket->subject }}</h3>
					<div class="bg-white redious-border p-20 p-md-30">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-30 d-flex gap-20 align-items-center justify-content-between">
									@if($ticket->status == 'open')
										<span class="badge badge-light-gray">{{ __('open') }}</span>
									@elseif($ticket->status == 'pending')
										<span class="badge badge-warning">{{ __('pending') }}</span>
									@elseif($ticket->status == 'answered')
										<span class="badge badge-success">{{ __('answered') }}</span>
									@elseif($ticket->status == 'close')
										<span class="badge badge-danger">{{ __('close') }}</span>
									@elseif($ticket->status == 'hold')
										<span class="badge badge-primary">{{ __('hold') }}</span>
									@endif

									<div class="d-flex flex-wrap gap-20">
                                        <span class="badge badge-light-gray text-capitalize">{{ __('priority') }} : {{ $ticket->priority }}</span>
										<span class="badge badge-light-gray">{{ __('department') }} : {{ $ticket->department->title }}</span>
										@if($replies && count($replies) > 0)
											<span class="badge badge-light-gray">{{ __('last_reply') }} : {{ \Carbon\Carbon::parse($replies->last()->created_at)->diffForHumans() }}</span>
										@endif
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="editor-wrapper mb-4">
									<textarea id="product-update-editor" name="reply"></textarea>
									<div class="nk-block-des text-danger">
										<p class="reply_error error"></p>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="custom-checkbox mb-12">
									<label>
										<input type="checkbox" value="1" name="return_to_list" checked>
										<span>{{ __('return_to_ticket_list') }}</span>
									</label>
								</div>
							</div>
							<div class="col-lg-12 input_file_div">
								<div class="mb-3">
									<label class="form-label mb-1">{{ __('file') }}</label>
									<label for="images"
									       class="file-upload-text"><p></p><span
												class="file-btn">{{__('choose_file') }}</span></label>
									<input class="d-none file_picker" type="file" id="images"
									       name="images">
									<div class="nk-block-des text-danger">
										<p class="images_error error">{{ $errors->first('images') }}</p>
									</div>
								</div>
								<div class="selected-files d-flex flex-wrap gap-20">
									<div class="selected-files-item">
										<img class="selected-img" src="{{ getFileLink('80x80', []) }}"
										     alt="favicon">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="bg-white redious-border p-20 p-md-30">
						<div class="row">
							<div class="d-flex justify-content-between">
								<h3 class="section-title">{{ __('ticket_details') }}</h3>
							</div>
							<div class="col-lg-12 mb-5">
								<div class="reply-card">
									<div class="row align-items-center">
										<div class="col-lg-11">
											<div class="ticket-content">
												{!! $ticket->body !!}
												@if(!empty($ticket->images))
													<span class="mt-2 d-block">
                                                                <a target="_blank" class="sg-text-primary"
                                                                   href="{{ getFileLink('295x248', $ticket->images) }}"
                                                                   download="">{{ __('download') }}</a>
                                                            </span>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between">
								<h3 class="section-title">{{ __('replies') }}</h3>
							</div>
							@if($replies && count($replies) > 0)
								@foreach($replies as $key=> $reply)
									<div class="col-lg-12">
										<div class="reply-card p-30 viewed {{ $key != 0 ? 'mt-4' : '' }}">
											<div class="row align-items-center">
												<div class="col-lg-3">
													<div class="submitter-info text-center mb-20 mb-lg-0">
														<h4>{{ $reply->user->name }}</h4>
														<p>{{ $reply->user->role->name }}</p>
														<p>{{ \Carbon\Carbon::parse($reply->created_at)->format('d-m-Y  H.i') }}</p>
													</div>
												</div>
												<div class="col-lg-9">
													<div class="ticket-content">
														{!! $reply->reply !!}
														@if(!empty($reply->images))
															<span class="mt-2 d-block">
                                                                <a target="_blank" class="sg-text-primary"
                                                                   href="{{ getFileLink('295x248', $reply->images) }}"
                                                                   download="">{{ __('download') }}</a>
                                                            </span>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="homepageFixBTN bg-white py-2 px-4">
			<button type="submit" class="btn sg-btn-primary">{{ __('submit_response') }}</button>
			@include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
		</div>
	</form>
	@include('backend.common.gallery-modal')
	@include('backend.common.delete-script')
@endsection

@push('css_asset')
	<link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@push('js_asset')

@endpush
@push('js')

	<script>
        $(document).ready(function () {
            $(document).on('change', '#ticket_update', function (e) {
                let value = $(this).val();

                let url = $(this).data('route');


                window.location.href = url + '?status=' + value;
            });
        });
	</script>
	<script>
        function downloadImages(imageSrc) {
            var link = document.createElement('a');
            link.href = imageSrc;
            link.download = 'image.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
	</script>
@endpush

