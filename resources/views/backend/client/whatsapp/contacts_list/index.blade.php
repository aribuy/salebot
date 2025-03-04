@extends('backend.layouts.master')
@section('title', __('contacts_list'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col col-lg-12 col-md-12">
					<div class="d-flex align-items-center justify-content-between mb-12">
						<h3 class="section-title">{{__('my_list')}}</h3>
						<div class="d-flex align-items-center gap-2">
							<div>
								<a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary gap-2"
								   data-bs-toggle="modal" data-bs-target="#contacts_list">
									<i class="las la-plus"></i>
									<span>{{__('create_list')}}</span>
								</a>
							</div>
						</div>
					</div>
					<div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
						<div class="row">
							<div class="col-lg-12">
								<div class="default-list-table table-responsive yajra-dataTable">
									{{ $dataTable->table() }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade" id="contacts_list" tabindex="-1" aria-labelledby="contacts_list" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content">
				<h6 class="sub-title create_sub_title">{{__('create_list') }}</h6>
				<button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
				@include('backend.client.whatsapp.contacts_list.create')
			</div>
		</div>
	</div>
	@include('backend.common.delete-script')
	@include('backend.client.whatsapp.contacts.modal.create')
@endsection

@push('js')
	{{ $dataTable->scripts() }}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
	<script>
        $(document).on('click', '.__add_contact', function () {
            let id = $(this).data('id');
            $('[name^="contact_list_id"]').remove();
            let formId = $('#__contact_modal_form');
            $('<input>').attr({
                type: 'hidden',
                name: 'contact_list_id[]',
                value: id
            }).appendTo(formId);
            $('#contactModal').modal('show');
        });

        $(document).on('submit', '#__contact_modal_form', function (event) {
            event.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const data = form.serialize();
            axios.post(url, data)
                .then(response => {
                    refreshDataTable();
                    toastr.success(response.data.message);
                    $('#__contact_modal_form')[0].reset();
                    setTimeout(() => {
                        $('#contactModal').modal("hide");
                    }, 300);
                })
                .catch(error => {
                    refreshDataTable();
                    if (typeof error.response.data === 'string') {
                        toastr.error(error.response.data);
                    }
                    var errors = error.response.data.errors || [];
                    for (let key in errors) {
                        let id = `#${key}`;
                        $(id).addClass('is-invalid');
                        $(id).siblings('.invalid-feedback').html(errors[key][0]);
                        $(id).siblings('.invalid-feedback').show();
                    }
                });
        });

        const refreshDataTable = () => {
            $('#__contact_modal_form')[0].reset();
            $('#contactModal').modal("hide");
            $('#dataTableBuilder').DataTable().ajax.reload();
        }

	</script>
	<script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
	</script>
@endpush





