@extends('backend.layouts.master')
@section('title', __('create_whatsapp_campaign'))
@push('css_asset')
    <link rel="stylesheet" href="{{ static_asset('admin/css/devices.min.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/template.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .flatpickr-wrapper {
            width: 100%;
        }

        #schedule_time_div {
            display: none;
        }

        .boot-file-input {
            height: 38px !important;
            padding-left: 12px !important;
        }

        .message.received {
            width: 90%;
        }

        .temp-pre {
            width: 320px;
            border-radius: 20px;
            min-height: 350px;
        }

        .conversation .conversation-container {
            min-height: 350px;
        }
    </style>
@endpush
@section('content')
    <div class="main-content-wrapper">
        <section class="oftions">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <h3 class="section-title">{{ __('send_whatsapp_notification') }}</h3>
                        <form action="{{ route('client.whatsapp.campaign.store') }}" id="campaign_store" method="post"
                            enctype="multipart/form-data">
                            {{-- novalidate --}}
                            @csrf
                            <div class="bg-white redious-border p-20 p-sm-30">
                                <div class="row" style="display: flex;">
                                    <div class="col-lg-6" style="display: flex; flex-direction: column;">
                                        <div class="row gx-20 add-coupon">
                                            <input type="hidden" class="is_modal" value="0" />
                                            <div class="col-lg-12">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label">{{ __('campaign_name') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control rounded-2" id="campaign_name"
                                                        name="campaign_name" value="{{ old('campaign_name') }}"
                                                        placeholder="{{ __('campaign_name') }}" required>
                                                    @if ($errors->has('campaign_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('campaign_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-4">
                                                    <label for="template_id" class="form-label">{{ __('templates') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select id="template_id" name="template_id"
                                                        class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                        aria-label=".form-select-lg example" required>
                                                        <option value="">{{ __('select_template') }}</option>
                                                        @foreach ($templates as $key => $template)
                                                            <option value="{{ $key }}" {{ old('template_id') }}>
                                                                {{ $template }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('template_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('template_id') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-4">
                                                    <label for="contact_list_id" class="form-label">
                                                        {{ __('contact_lists') }}
                                                    </label>
                                                    <select id="contact_list_id" name="contact_list_id[]"
                                                        class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                        aria-label=".form-select-lg example" multiple>
                                                        <option value="" disabled>{{ __('select_contact_list') }}
                                                        </option>
                                                        @foreach ($contact_lists as $key => $contact_list)
                                                            <option value="{{ $key }}"
                                                                {{ old('contact_list_id') == $key ? 'selected' : '' }}>
                                                                {{ $contact_list }}</option>
                                                        @endforeach
                                                        {{-- <option value="all">{{ __('all_contacts') }}</option> --}}
                                                    </select>
                                                    @if ($errors->has('contact_list_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('contact_list_id') }}</p>
                                                        </div>
                                                    @endif
                                                    <small class="form-text text-muted">
                                                        {{ __('to_send_to_all_contact_list_leave_the_selection_blank') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-4">
                                                    <label for="segment_id" class="form-label">
                                                        {{ __('segment') }}
                                                    </label>
                                                    <select id="segment_id" name="segment_id[]"
                                                        class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                        aria-label=".form-select-lg example" multiple>
                                                        <option value="" disabled>{{ __('select_segment') }}</option>
                                                        @foreach ($segments as $key => $segment)
                                                            <option value="{{ $key }}"
                                                                {{ old('segment_id') == $key ? 'selected' : '' }}>
                                                                {{ $segment }}</option>
                                                        @endforeach
                                                        {{-- <option value="all" selected>{{ __('all_segment') }}</option> --}}
                                                    </select>
                                                    @if ($errors->has('segment_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('segment_id') }}</p>
                                                        </div>
                                                    @endif
                                                    <small class="form-text text-muted">
                                                        {{ __('to_send_to_all_segment_leave_the_selection_blank') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <label class="custom-control-label" for="send_scheduled">
                                                        <input type="checkbox" class="custom-control-input read common-key"
                                                            name="send_scheduled" value="1" id="send_scheduled">
                                                        <span>{{ __('send_at_a_specified_date_and_time') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 schedule_time_div" id="schedule_time_div">
                                                <div class="mb-4">
                                                    <label for="schedule_time"
                                                        class="form-label">{{ __('schedule_time') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" value="{{ old('schedule_time') }}"
                                                        class="form-control rounded-2" id="schedule_time"
                                                        name="schedule_time" placeholder="{{ __('schedule_time') }}">
                                                    @if ($errors->has('schedule_time'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('schedule_time') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div id="total_contact">{{ __('total_contact') }} : <span
                                                        id="total-display">0</span></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-start align-items-center mt-30">
                                                    <button type="submit"
                                                        class="btn sg-btn-primary">{{ __('send') }}</button>
                                                    @include('backend.common.loading-btn', [
                                                        'class' => 'btn sg-btn-primary',
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" style="display: flex; flex-direction: column;">
                                        <div class="card h-100 border-0">
                                            <div class="card-body" id="load-template">
                                                <div class="accordion border-0" id="accordionPreview">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button py-2" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapsePreview" aria-expanded="true"
                                                                aria-controls="collapsePreview">
                                                                {{ __('preview') }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapsePreview" class="accordion-collapse collapse show"
                                                            data-bs-parent="#accordionPreview">
                                                            <div class="accordion-body">
                                                                <div class="whatsapp-preview">
                                                                    {{-- <div class="marvel-device nexus5"> --}}
                                                                    <div class="temp-pre">
                                                                        <div class="screen ">
                                                                            <div class="screen-container">
                                                                                <div class="screen-container">
                                                                                    <div class="chat">
                                                                                        <div class="chat-container">
                                                                                            <div class="conversation">
                                                                                                <div
                                                                                                    class="conversation-container">
                                                                                                    <div
                                                                                                        class="message received card">
                                                                                                        <div id="message-header1"
                                                                                                            class="message-header1 py-2">
                                                                                                            {{ __('template_header_demo') }}
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="message-body mb-2">
                                                                                                            <p>{{ __('please_select_your_whatsapp_campaign_template') }}
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="message-footer">
                                                                                                            <div
                                                                                                                id="_footer_text">
                                                                                                                Please
                                                                                                                select the
                                                                                                                template
                                                                                                                that best
                                                                                                                suits your
                                                                                                                campaign
                                                                                                                objectives.
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-start align-items-center mt-30">
                                            <button type="submit"
                                                class="btn sg-btn-primary">{{ __('send') }}</button>
                                            @include('backend.common.loading-btn', [
                                                'class' => 'btn sg-btn-primary',
                                            ])
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js_asset')
    <script>
        window.translations = {!! json_encode(json_decode(file_get_contents(base_path('lang/en.json')), true)) !!};
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="{{ static_asset('admin/js/custom/template.js') }}"></script>
    <script>
        var get_template = "{{ route('client.template.get', ['id' => '__template_id__']) }}";
        var contact_count_url = "{{ route('client.whatsapp.campaign.count-contact') }}";
        $(document).ready(function() {
            $('#schedule_time').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                static: true,
                // Use "now" to set the default date and time to current
                // defaultDate: "now",
                // Additional options for minute input
                minuteIncrement: 5, // Set minute increment
                allowInput: true // Allow manual input for minutes
            });

            $(document).on('input', '.live_preview', function() {
                var targetId = $(this).data('target');
                var newValue = $(this).val();
                $(targetId).text(newValue);
            });

            // $('#contact_list_id').on('change', function() {
            //     var selectedOptions = $(this).val();
            //     var allOption = "all";
            //     if (selectedOptions.includes(allOption) && selectedOptions.length > 1) {
            //         $(this).val([allOption]).trigger('change');
            //     } else if (selectedOptions.includes(allOption) && selectedOptions.length === 1) {
            //         return;
            //     } else {
            //         var newSelections = selectedOptions.filter(function(value) {
            //             return value !== allOption;
            //         });
            //         $(this).val(newSelections).trigger('change');
            //     }
            // });


        })
    </script>
    <script src="{{ static_asset('admin/js/custom/campaign.js') }}"></script>
    <script src="{{ static_asset('admin/js/custom/campaign/create.js') }}"></script>
@endpush
