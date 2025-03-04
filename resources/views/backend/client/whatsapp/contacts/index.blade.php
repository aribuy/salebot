@extends('backend.layouts.master')
@section('title', __('contacts'))
@section('content')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

<style>
    .select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}
.flatpickr-wrapper {
    width: 100%;
}
</style>
    
@endpush
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{ __('contacts_management') }}</h3>
                    <div class="oftions-content-right mb-12 gap-2">
                        <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary" id="filterBTN">
                            <i class="las la-filter"></i>
                        </a>
                        <div class="dropdown custom-dropdown d-none">
                            <button class="btn sg-btn-primary" type="button" id="dropdownMenu2" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ __('bulk_action') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <li class="dropdown-submenu">
                                    <button class="dropdown-item" type="button">{{ __('add_list') }}</button>
                                    <ul class="dropdown-menu right-submenu">
                                        @foreach ($lists as $list)
                                            <li>
                                                <button class="dropdown-item add_list" data-list-id="{{ $list->id }}"
                                                    type="button">{{ $list->name }}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li>
                                    <button class="dropdown-item remove_list"
                                        type="button">{{ __('remove_list') }}</button>
                                </li>
                                <li class="dropdown-submenu">
                                    <button class="dropdown-item" type="button">{{ __('add_segment') }}</button>
                                    <ul class="dropdown-menu right-submenu">
                                        @foreach ($segments as $segment)
                                            <li>
                                                <button class="dropdown-item add_segment"
                                                    data-segment-id="{{ $segment->id }}"
                                                    type="button">{{ $segment->title }}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li>
                                    <button class="dropdown-item remove_segment"
                                        type="button">{{ __('remove_segment') }}</button>
                                </li>
                                <li>
                                    <button class="dropdown-item blacklist"
                                        type="button">{{ __('add_blacklist') }}</button>
                                </li>
                                <li>
                                    <button class="dropdown-item remove_blacklist"
                                        type="button">{{ __('remove_blacklist') }}</button>
                                </li>
                            </ul>
                        </div>

                        <a href="{{ route('client.contact.create') }}"
                            class="d-flex align-items-center btn sg-btn-primary">
                            <i class="las la-plus"></i>
                            <span>{{ __('add_new_contacts') }}</span>
                        </a>
                        <a href="{{ route('client.segments.index') }}"
                            class="d-flex align-items-center btn sg-btn-primary">
                            <span>{{ __('segments') }}</span>
                        </a>
                        <a href="{{ route('client.contact.import') }}"
                            class="d-flex align-items-center btn sg-btn-primary">
                            <span>{{ __('Imports') }}</span>
                        </a>
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
                                    <div class="">
                                        <label for="phone" class="form-label">{{ __('phone') }}</label>
                                        <input type="number" class="form-control rounded-2 filterable" id="phone"
                                            name="phone" placeholder="{{ __('phone') }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="phone_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="contact_list_id" class="form-label">{{ __('contacts_list') }}</label>
                                        <div class="select-type-v1 list-space">
                                            <select class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                id="contact_list_id" name="contact_list_id"
                                                aria-label=".form-select-lg example">
                                                <option value="" selected>{{ __('select_contact_list') }}</option>
                                                @foreach ($lists as $list)
                                                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                                                @endforeach
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
                                            <select class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                id="segments_id" name="segments_id" aria-label=".form-select-lg example">
                                                <option value="" selected>{{ __('select_segments') }}</option>
                                                @foreach ($segments as $segment)
                                                    <option value="{{ $segment->id }}">{{ $segment->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="nk-block-des text-danger">
                                            <p class="segments_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="country" class="form-label">{{ __('country') }}</label>
                                        <div class="select-type-v1 list-space">
                                            <select
                                                class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                aria-label=".form-select-lg example" id="country_id" name="country_id">
                                                <option value="" selected>{{ __('select_country') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ __($country->name) }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('country_id'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ str_replace('id', '', $errors->first('country_id')) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">{{ __('status') }}</label>
                                        <div class="select-type-v1 list-space">
                                            <select
                                                class="form-select form-select-lg rounded-0 mb-3 without_search filterable"
                                                aria-label=".form-select-lg example" id="status" name="status">
                                                <option value="">{{ __('all') }}</option>
                                                <option value="1">{{ __('active') }}</option>
                                                <option value="0">{{ __('inactive') }}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="status_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="is_blacklist" class="form-label">{{ __('is_blacklist') }}</label>
                                        <div class="select-type-v1 list-space">
                                            <select
                                                class="form-select form-select-lg rounded-0 mb-3 without_search filterable"
                                                aria-label=".form-select-lg example" id="is_blacklist"
                                                name="is_blacklist">
                                                <option value="">{{ __('all') }}</option>
                                                <option value="1">{{ __('yes') }}</option>
                                                <option value="0">{{ __('no') }}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="is_blacklist_error error"></p>
                                            </div>
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
                <div
                    class="default-tab-list table-responsive default-tab-list-v2 activeItem-bd-md bg-white redious-border p-20 p-sm-30">
                    <div class="default-list-table yajra-dataTable">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="template_modal" tabindex="-1" aria-labelledby="credit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <h6 class="sub-title create_sub_title">{{ __('templates') }}</h6>
                <button type="button" class="btn-close modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="row gx-20 add-coupon">
                    @foreach ($templates as $row)
                        <div class="col-lg-12">
                            <a class="send-template-link" data-template="{{ $row->id }}"
                                href="{{ route('client.send.template', ['template_id' => $row->id, 'contact_id' => null]) }}"
                                target="_blank">
                                <div class="mb-4 canned_response_div">
                                    <h6>{{ $row->name }}</h6>
                                    <span>{{ __('category') }} : {{ $row->category }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('backend.client.whatsapp.contacts.modal.view')
@endsection
@include('backend.common.block-script')
{{-- @include('backend.common.delete-script') --}}
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    
    {{ $dataTable->scripts() }}
    <script>
        const blacklistUrl = "{{ route('client.contact.blacklist') }}"
        const removeBlacklistUrl = "{{ route('client.remove.blacklist') }}"
        const removelistUrl = "{{ route('client.remove.list') }}"
        const addListUrl = "{{ route('client.add.list') }}"
        const addSegmentUrl = "{{ route('client.add.segment') }}"
        const removeSegmentUrl = "{{ route('client.remove.segment') }}"
        var get_contact = "{{ route('client.contact.view', ['id' => '__contact_id__']) }}";
    </script>
    <script src="{{ static_asset('admin/js/custom/contact.js') }}?v={{ time() }}"></script>
@endpush
