@extends('backend.layouts.master')
@section('title', __('whatsapp_campaigns'))
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
                        <h3 class="section-title">{{ __('whatsapp_campaigns') }}</h3>
                        <div class="oftions-content-right mb-12 gap-2">
                            <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary" id="filterBTN">
                                <i class="las la-filter"></i>
                            </a>
                            <a href="{{ route('client.whatsapp.campaign.create') }}"
                                class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{ __('new_campaign') }}</span>
                            </a>
                            @if (env('APP_DEBUG'))
                                <a href="{{ route('cron.run.manually') }}"
                                    class="d-flex align-items-center btn sg-btn-primary gap-2">
                                    <span>{{ __('run_cron_manually') }}</span>
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
                                            <label for="created_at" class="form-label">{{ __('date') }}</label>
                                            <input type="text" class="form-control rounded-2 filterable" id="created_at"
                                                name="created_at" placeholder="YYYY-MM-DD to YYYY-MM-DD">
                                            <div class="nk-block-des text-danger">
                                                <p class="name_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="contact_list_id"
                                                class="form-label">{{ __('contacts_list') }}</label>
                                            <div class="select-type-v1 list-space">
                                                <select
                                                    class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                    id="contact_list_id" name="contact_list_id"
                                                    aria-label=".form-select-lg example">
                                                    <option value="" selected>{{ __('select_contact_list') }}</option>
                                                    @if (!empty($lists))
                                                        @foreach ($lists as $key => $list)
                                                            <option value="{{ $key }}">{{ $list }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="nk-block-des text-danger">
                                                <p class="segments_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="segments" class="form-label">{{ __('segments') }}</label>
                                            <div class="select-type-v1 list-space">
                                                <select
                                                    class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                    id="segments_id" name="segments_id"
                                                    aria-label=".form-select-lg example">
                                                    <option value="" selected>{{ __('select_segments') }}</option>
                                                    @if (!empty($segments))
                                                        @foreach ($segments as $key => $segment)
                                                            <option value="{{ $key }}">{{ $segment }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="nk-block-des text-danger">
                                                <p class="segments_error error"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="template" class="form-label">{{ __('template') }}</label>
                                            <div class="select-type-v1 list-space">
                                                <select
                                                    class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                    aria-label=".form-select-lg example" id="template_id"
                                                    name="template_id">
                                                    <option value="" selected>{{ __('select_template') }}</option>
                                                    @if (!empty($templates))
                                                        @foreach ($templates as $key => $template)
                                                            <option value="{{ $key }}">{{ __($template) }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if ($errors->has('template_id'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ str_replace('id', '', $errors->first('template_id')) }}</p>
                                                    </div>
                                                @endif
                                            </div>
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
    @include('backend.client.whatsapp.campaigns.partials.__js_resend_modal')
@endsection
@push('js')
    {{ $dataTable->scripts() }}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ static_asset('admin/js/custom/campaign.js') }}"></script>
    <script>
        const refreshDataTable = () => {
            $('#dataTableBuilder').DataTable().ajax.reload();
        }
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
                            console.log(error);
                            refreshDataTable();
                        })
                }
            });
        };
   
    </script>
@endpush
