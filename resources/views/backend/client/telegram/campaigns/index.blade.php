@extends('backend.layouts.master')
@section('title', __('campaigns'))
@push('css_asset')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .flatpickr-wrapper {
            width: 100%;
        }

        #schedule_time_div {
            display: none;
        }
    </style>
@endpush
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col col-lg-12 col-md-12">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{ __('telegram_campaigns') }}</h3>
                        <div class="oftions-content-right mb-12 gap-2">
                            <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary" id="filterBTN">
                                <i class="las la-filter"></i>
                            </a>
                            <a href="{{ route('client.telegram.campaign.create') }}"
                            class="d-flex align-items-center btn sg-btn-primary gap-2">
                            <i class="las la-plus"></i>
                            <span>{{ __('new_campaign') }}</span>
                        </a>
                        @if(env("APP_DEBUG"))
                            <a href="{{ route('cron.run.manually') }}"
                                class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <span>{{__('run_cron_manually')}}</span>
                            </a>
                        @endif
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="col-lg-12" id="filterSection">
                            <div class="hidden-filter bg-white redious-border p-20 p-sm-30 mb-2">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('name') }}</label>
                                            <input type="text" class="form-control rounded-2 filterable" id="name"
                                                name="name" placeholder="{{ __('name') }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="name_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="created_at">{{ __('date') }}</label>
                                            <input type="text" name="created_at" id="date-range"
                                                class="form-control date-picker filterable"
                                                placeholder="YYYY-MM-DD to YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn sg-btn-primary w-80 mt-10 d-flex justify-end"
                                        id="filter">{{ __('filter') }}</button>
                                    <button type="submit" class="btn sg-btn-primary  w-80 mt-10 d-flex justify-end"
                                        id="reset">{{ __('reset') }}</button>
                                </div>
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
    @include('backend.common.delete-script')
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{ $dataTable->scripts() }}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script>
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
        });
        $(document).on('click', '.__add_contact', function() {
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

        $(document).on('submit', '#__contact_modal_form', function(event) {
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
            $('#dataTableBuilder').DataTable().ajax.reload();
        }
        $(document).ready(function() {
            $('#filterBTN').click(function() {
                $('#filterSection').toggleClass('show');
            });

            const advancedSearchMapping = (attribute) => {
                console.log(attribute);
                $('#dataTableBuilder').on('preXhr.dt', function(e, settings, data) {
                    data[attribute.key] = attribute.value;
                });
            }

            $(document).on('change', '.filterable', function() {
                advancedSearchMapping({
                    key: $(this).attr('name'),
                    value: $(this).val(),
                });
            });

            $(document).on('click', '#reset', () => {
                $('.filterable').val('').trigger('change');
                $('#dataTableBuilder').DataTable().ajax.reload();
            });

            $(document).on('click', '#filter', () => {
                $('#checkAll').prop('checked', false).trigger('change');
                $('#dataTableBuilder').DataTable().ajax.reload();
            });
        });


        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });

        $(document).on('click', '.__js_update_status', function() {
            confirmationAlert(
                $(this).data('url'),
                $(this).data('status'),
                'Yes, Update It!'
            )
        })

        const confirmationAlert = (url, status, button_test = 'Yes, Confirmed it!') => {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: button_test,
            }).then((confirmed) => {
                if (confirmed.isConfirmed) {
                    axios.post(url, {
                            status: status
                        })
                        .then(response => {
                            refreshDataTable();
                            Swal.fire(
                                response.data.message,
                                '',
                                response.data.status == true ? 'success' : 'error',
                            );
                        })
                        .catch(error => {
                            refreshDataTable();
                        })
                }
            });
        };
    </script>
@endpush
