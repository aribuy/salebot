@extends('backend.layouts.master')
@section('title', __('contacts'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{ __('subscriber_management') }}</h3>
                    <div class="oftions-content-right mb-12 gap-2">
                        <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary" id="filterBTN">
                            <i class="las la-filter"></i>
                        </a>
   
                    </div>
                </div>
                <div class="row col-lg-12">
                    <div class="col-lg-12" id="filterSection">
                        <div class="hidden-filter bg-white redious-border p-20 p-sm-30 mb-2">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="group_id" class="form-label">{{ __('group') }}<span
                                                class="text-danger">*</span></label>
                                        <select id="group_id" name="group_id"
                                            class="multiple-select-1 form-select-lg rounded-0 mb-3 filterable"
                                            aria-label=".form-select-lg example">
                                            <option value="">{{ __('select_group') }}</option>
                                            @if (isset($groups))
                                            @foreach ($groups as $key => $group)
                                                <option value="{{ $key }}">{{ $group }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('group_id'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('group_id') }}</p>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="is_bot" class="form-label">{{ __('is_bot') }}</label>
                                        <div class="select-type-v1 list-space">
                                            <select
                                                class="form-select form-select-lg rounded-0 mb-3 without_search filterable"
                                                aria-label=".form-select-lg example" id="is_bot" name="is_bot">
                                                <option value="">{{ __('all') }}</option>
                                                <option value="1">{{ __('yes') }}</option>
                                                <option value="0">{{ __('no') }}</option>
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
    @include('backend.common.delete-script')
@endsection
@push('js')

    {{ $dataTable->scripts() }}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="{{ static_asset('admin/js/custom/telegram.js') }}?v=1.0.0"></script>   

    <script>
        $(document).ready(function() {
            $('.dropdown-submenu').on('click', function(event) {
                $('.dropdown-submenu ul').removeClass('show');
                $(this).find('ul').toggleClass('show');
                event.stopPropagation();
            });
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.dropdown-submenu').length) {
                    $('.dropdown-submenu ul').removeClass('show');
                }
            });
        });




        $(document).ready(function() {
            $('#filterBTN').click(function() {
                $('#filterSection').toggleClass('show');
            });

            const advancedSearchMapping = (attribute) => {
                $('#dataTableBuilder').on('preXhr.dt', function(e, settings, data) {
                    data[attribute.key] = attribute.value;
                });
            }

            $(document).on('change', '.filterable', function() {
                advancedSearchMapping({
                    key: $(this).attr('id'),
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
    </script>
@endpush
