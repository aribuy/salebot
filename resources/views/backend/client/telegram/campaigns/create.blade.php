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
    <div class="main-content-">
        <section class="oftions">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h3 class="section-title">{{ __('send_new_campaigns') }}</h3>
                        <form action="{{ route('client.telegram.campaign.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white redious-border p-20 p-sm-30">
                                <div class="row gx-20 add-coupon">
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
                                            <label for="name" class="form-label">{{ __('text_message') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" class="form-control rounded-2" id="text_message"
                                                name="text_message" placeholder="{{ __('text_message') }}" required>{{ old('text_message') }}</textarea>
                                            @if ($errors->has('text_message'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('text_message') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="contact_id" class="form-label">{{ __('contact') }}<span
                                                    class="text-danger">*</span></label>
                                            <select id="contact_id" name="contact_id[]"
                                                class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                aria-label=".form-select-lg example" multiple>
                                                <option value="">{{ __('select_contact') }}</option>
                                                @foreach ($contacts as $key => $contact)
                                                    <option value="{{ $key }}">{{ $contact }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('contact_id'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('contact_id') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-12 mb-2">
                                        <label for="time_zone" class="form-label">{{ __('time_zone') }}</label>
                                        <select class="form-select form-select-lg mb-3 with_search" name="time_zone"
                                            id="time_zone" required>
                                            @foreach ($time_zones as $time_zone)
                                                <option value="{{ $time_zone->id }}"
                                                    {{ $time_zone->id == old('time_zone',setting('time_zone')) ? 'selected' : '' }}>
                                                    {{ $time_zone->gmt_offset > 0 ? "(UTC +$time_zone->gmt_offset)" . ' ' . $time_zone->timezone : $time_zone->gmt_offset }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="nk-block-des text-danger">
                                            <p class="time_zone_error error">{{ $errors->first('time_zone') }}</p>
                                        </div>
                                </div> --}}
                                    <div class="col-lg-12 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <label class="custom-control-label" for="send_scheduled">
                                                <input type="checkbox" class="custom-control-input read common-key"
                                                    name="send_scheduled" value="1" id="send_scheduled">
                                                <span>{{ __('send_scheduled') }}</span>
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
                                    <div class="d-flex justify-content-end align-items-center mt-30">
                                        <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
                                        @include('backend.common.loading-btn', [
                                            'class' => 'btn sg-btn-primary',
                                        ])
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
@endsection
@push('js_asset')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            const my_flatpickr = $('#schedule_time').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                static: true
            });
            var get_template = "{{ route('client.template.get', ['id' => '__template_id__']) }}";
            $('#template_id').on('change', function(e) {
                let template_id = $(this).val();
                if (template_id.trim() !== '') {
                    let url = get_template.replace('__template_id__', template_id);
                    axios.get(url, {
                            params: {
                                id: template_id
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .then(response => {
                            console.log(response);
                            $('#load-template').html(response.data);
                        })
                        .catch(error => {
                            toastr.error(error.message);
                        });
                } else {
                    console.log('Template ID is empty');
                }
            });
            $('#load-template').on('change', '.body-match-select', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'input_value') {
                    $(this).closest('.match-value-select').next('.body-value-input').show();
                } else {
                    $(this).closest('.match-value-select').next('.body-value-input').hide();
                }
            });
            $(document).on('input', '.live_preview', function() {
                var targetId = $(this).data('target');
                var newValue = $(this).val();
                $(targetId).text(newValue);
            });
            $(document).ready(function() {
                $('#send_scheduled').on('change', function() {
                    $('#schedule_time_div').toggle($(this).prop('checked'));
                    $('#schedule_time').prop('required', $(this).prop('checked'));
                });
                // Manually validate the field on form submit
                $('#campaign_store').on('submit', function(event) {
                    if ($('#send_scheduled').prop('checked')) {
                        if ($('#schedule_time').val().trim() === '') {
                            event.preventDefault();
                            toastr.error('Please select a schedule time.');
                        }
                    }
                });
            });


        })
    </script>
@endpush
