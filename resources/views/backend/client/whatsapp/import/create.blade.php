@extends('backend.layouts.master')
@section('title')
    {{ __('Imports') }}
@endsection
@section('content')
    @push('css_asset')
        <link rel="stylesheet" href="{{ static_asset('admin/css/jsuites.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ static_asset('admin/css/jexcel.css') }}" type="text/css" />
        <style>
            .input {
                width: 100% !important;
                border: none;
            }

            table th,
            table td {
                border: 1px solid #a3a0a0;
            }

            table th,
            table .sl {
                color: #000000fa;
            }

            .validation-error {
                background-color: red;
                /* Light red background */
                border: 1px solid red;
                /* Red border */
            }

            .jexcel-content select {
                z-index: 1000;
                /* Adjust the value as needed */
            }

            .jexcel {
                overflow: visible;
            }

            .jexcel-content {
                position: relative;
            }

            #spreadsheet {
                overflow: auto;
                /* or overflow: scroll; */
            }

            .jexcel {
                width: 100%;
            }

            input#upload_csv_field {
                padding-left: 11px !important;
                height: 38px !important;
            }
                    /* This class changes the color to red and makes the text bold to indicate a required field */
        .jexcel_required {
            color: red;
            font-weight: bold;
        }

        </style>
    @endpush

    <div class="container-fluid">
        <div class="row gx-20">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center mb-12">
                    <h3 class="section-title">{{ __('Imports') }}</h3>
                    <div class="oftions-content-right">
                        <a href="{{ url()->previous() }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                            <i class="las la-arrow-left"></i>
                            <span>{{ __('back') }}</span>
                        </a>
                    </div>
                </div>
                <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-inner">
                             
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="mb-3">
                                            <label for="choose_file" class="form-label">{{ __('choose_file') }}</label>
                                            <input class="form-control" name="file" type="file" id="upload_csv_field"
                                                accept=".xlsx">
                                                <small class="text-danger"><strong>{{ __('note') }}:</strong>
                                                    {{ __('upload_xlsx_alert') }} 
                                                    <a href="{{ route('client.contacts.export') }}" class="alert-link" download="">{{ __('download_sample_file_here') }}</a>.
                                                </small>
                                        </div>
                                        @if ($errors && $errors->any())
                                            @foreach ($errors->all() as $error)
                                                <div class="invalid-feedback help-block">
                                                    <p>{{ $error }}</p>
                                                </div>
                                            @endforeach
                                        @endif
                                        
                                    </div>
                                    <div class="col-12">
                                       
                                    </div>
                                </div>
                                <div id="spreadsheet" style="width: 100%; overflow-x: scroll; overflow-y: auto;">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-4">
                                            <button
                                                class="btn btn-primary confirm_order">{{ __('confirm_upload') }}</button>
                                            <button class="btn btn-outline-secondary"
                                                id="reset_btn">{{ __('reset_table') }}</button>
                                                <a class="btn btn-primary  import-sample-btn"
                                                href="{{ route('client.contacts.export') }}">
                                                 <span><i class="icon las la-file-download"></i></span>
                                                 <span>{{ __('sample') . ' ' . __('download') }}</span>
                                             </a>
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
@endsection
@push('js')
    <script src="{{ static_asset('admin/js/jexcel.js') }}"></script>
    <script src="{{ static_asset('admin/js/jsuites.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
        var is_confirm_clicked = false;
        $(document).ready(function() {
            const container = document.getElementById('spreadsheet');
            let data = [
                [], // Row 1
                [], // Row 2
                [], // Row 3
                [], // Row 4
                [], // Row 5
                [], // Row 6
                [], // Row 7
                [], // Row 8
                [], // Row 9
                [], // Row 10
            ]; 
            let columns = [{
                    title: '{{ __('name') }}',
                    type: 'text',
                    width: 120,
                    allowBlank: false,
                    align: 'left', // Set text alignment to left for this column
                },
                {
                    title: '{{ __('phone') }}', // Add a span with class red
                    type: 'text',
                    width: 120,
                    allowBlank: false,
                    align: 'left', // Set text alignment to left for this column,
                    cssClass: 'jexcel_required', // Apply custom CSS class for styling

                },
                {
                    title: '{{ __('contact_lists') }}',
                    type: 'dropdown',
                    source: @json($lists),
                    width: 120,
                    allowBlank: false, 
                    multiple:true
                },
                {
                    title: '{{ __('segments') }}',
                    type: 'dropdown',
                    source: @json($segments),
                    width: 120,
                    allowBlank: false,
                    multiple:true
                },
            ];
            var spreadsheet = jspreadsheet(container, {
                data: data,
                columns: columns,
                columnDrag: true,
                rowResize: true,
                scrolling: true,
                textAlign: 'left',
                oninsertcolumn: function (obj) {
                    var $headerCell = $('.jexcel_th .label', container).eq(1);
                    $headerCell.html('{{ __('phone') }} * <span style="color: red;">({{ __('with_country_phonecode') }})</span>');
                }
            });

            // Ensure container styles
            $("#spreadsheet").css({
                overflow: "auto", // or "scroll"
                // text-align: 'left'
            });
            $('#spreadsheet').css('text-align', 'left'); // Set text alignment globally to left
            $(document).on('change', '#upload_csv_field', function() {
                let contact_list_id = $('#contact_list_id').val();
                let segment_id = $('#segment_id').val();
                let file = $(this)[0].files[0];
                let formData = new FormData();
                formData.append('file', file);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('contact_list_id', contact_list_id);
                formData.append('segment_id', segment_id);
                $.ajax({
                    url: '{{ route('client.contact.parse.csv') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response.data.rows);
                        spreadsheet.insertRow(10);
                        $('#spreadsheet').jspreadsheet('setData', response.data.rows);
                    }
                });
            });
            //reset table
            $(document).on('click', '#reset_btn', function(e) {
                e.preventDefault();
                if (confirm('{{ __('are_you_sure_to_reset') }}')) {
                    $('#spreadsheet').jspreadsheet('setData', [
                        [],
                        [],
                        [],
                        [],
                        [],
                        [],
                        [],
                        [],
                        [],
                        []
                    ]);
                    document.getElementById('upload_csv_field').value = '';
                    $('#segment_id').val([]).change(); // Clear and trigger change event
                    $('#contact_list_id').val([]).change(); // Clear and trigger change event

                }
            });
            $(document).on('click', '.confirm_order', function() {
                let contact_list_id = $('#contact_list_id').val();
                let segment_id = $('#segment_id').val();
                is_confirm_clicked = true;
                $(this).prop('disabled', true);
                $(this).html(
                    '<span class="spinner-border me-1" role="status" aria-hidden="true"></span>{{ __('saving') }}...'
                );
                let data = spreadsheet.getData();
                let formData = new FormData();
                formData.append('data', JSON.stringify(data));
                formData.append('_token', '{{ csrf_token() }}');
                // Check if contact_list_ids is an array and append each item separately
                if (Array.isArray(contact_list_id)) {
                    contact_list_id.forEach((id) => {
                        formData.append('contact_list_id[]',
                        id); // Use square brackets to indicate array
                    });
                } else {
                    formData.append('contact_list_id[]',
                    contact_list_id); // If not array, append as single value
                }
                // Same for segment_ids
                if (Array.isArray(segment_id)) {
                    segment_id.forEach((id) => {
                        formData.append('segment_id[]',
                        id); // Use square brackets to indicate array
                    });
                } else {
                    formData.append('segment_id[]', segment_id); // If not array, append as single value
                }
                $.ajax({
                    url: '{{ route('client.contact.confirm-upload') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        let selector = $('.confirm_order');
                        if (response.success) {
                            is_confirm_clicked = false;
                            selector.html('{{ __('confirm_upload') }}');
                            selector.prop('disabled', false);
                            toastr.success(response.message);
                            $('#spreadsheet').jspreadsheet('setData', [
                                [],
                                [],
                                [],
                                [],
                                [],
                                [],
                                [],
                                [],
                                [],
                                []
                            ]);
                            document.getElementById('upload_csv_field').value = '';
                            $('#segment_id').val([]).change(); // Clear and trigger change event
                            $('#contact_list_id').val([])
                        .change(); // Clear and trigger change event

                        } else {
                            selector.html('{{ __('confirm_upload') }}');
                            selector.prop('disabled', false);
                            toastr.error(response.message);
                        }
                    },
                    error: function(error) {
                        let selector = $('.confirm_order');
                        selector.prop('disabled', false);
                        selector.html('{{ __('confirm_upload') }}');
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            let error_length = errors.length;
                            const table = document.querySelector('.jexcel');
                            const cells = table.querySelectorAll('td');
                            cells.forEach(function(cell) {
                                cell.classList.remove('validation-error');
                            });
                            let cells_length = error.responseJSON.rows.length;
                            for (let i = 0; i < cells_length; i++) {
                                let cell = error.responseJSON.rows[i];
                                let x = cell.x;
                                let y = cell.y;
                                cells.forEach(function(cell) {
                                    let cell_x = cell.dataset.x;
                                    let cell_y = cell.dataset.y;
                                    if (cell_x == x && cell_y == y) {
                                        cell.classList.add('validation-error');
                                    }
                                });
                            }
                            for (let i = 0; i < error_length; i++) {
                                let row_error = errors[i];
                                cells.forEach(function(cell) {
                                    let x = cell.dataset.x;
                                    let y = cell.dataset.y;
                                    if (x == 0 && row_error.row == y && row_error
                                        .merchant_error) {
                                        cell.classList.add('validation-error');
                                    }
                                    if (x == 1 && row_error.row == y && row_error
                                        .shop_error) {
                                        cell.classList.add('validation-error');
                                    }
                                });
                            }
                            toastr.error(error.responseJSON.message);
                        } else {
                            toastr.error('{{ __('something_went_wrong') }}');
                        }
                    }
                });
            });
        });
    </script>
@endpush
