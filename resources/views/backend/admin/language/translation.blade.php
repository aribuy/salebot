@extends('backend.layouts.master')
@section('title', __('languages'))
@push('css')
    <style>
        .default-list-table .table td,
        .default-list-table .table th {
            padding: 15px 30px;
            color: #7e7f92;
            white-space: wrap;
            min-width: 350px !important;

        }
    </style>
@endpush
 
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row mb-30 justify-content-end">
                        <div class="col-lg-12">
                            <div class="header-top d-flex justify-content-between align-items-center">
                                <h3 class="section-title">{{ __('translation_keys') }}</h3>
                            </div>
                            <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30" data-select2-id="select2-data-10-togg">
                                <div class="row">
                                    <div class="col-lg-12" data-select2-id="select2-data-9-e6eh">
                                        <div class="mb-2">
                                            <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#searchAndReplaceModal" class="btn btn-sm btn-primary">
                                            <i class="la la-exchange-alt"></i>
                                            {{ __('search_and_replace') }}
                                        </button>
                                        @if (env('APP_DEBUG'))
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#addKeywordModal"
                                            class="btn btn-sm btn-primary">
                                            <i class="la la-plus"></i>
                                            {{ __('add_new_key') }}
                                        </button>
                                        @endif
                                        @if (env('APP_DEBUG'))
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ route('admin.language.scan', $language->id) }}"
                                            title="{{ __('sync_missing_keywords') }}">
                                            <i class="las la-sync-alt"></i>
                                            {{ __('sync_missing_keywords') }}
                                        </a>
                                        @endif
                                        @if ($language->locale !=='en')
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ route('admin.language.missing-keys', $language->id) }}"
                                            title="{{ __('import_missing_keywords_from_en') }}">
                                            <i class="las la-upload"></i>
                                            {{ __('import_missing_keywords_from_en') }}
                                        </a>
                                        @endif
                                        </div>
                                        <form action="{{ route('language.translations.page') }}" method="get">
                                            <div class="row justify-content-end">
                                                <div class="col-lg-3">
                                                    <div class="mb-4">
                                                        <select name="lang" class="without_search"
                                                            placeholder="{{ __('languages') }}" aria-hidden="true">
                                                            @foreach ($languages as $lang)
                                                                <option value="{{ $lang->id }}"
                                                                    {{ $lang->id == $language->id ? 'selected' : '' }}>
                                                                    {{ $lang->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="inputGroup"
                                                            name="q" placeholder="{{ __('search') }}"
                                                            value="{{ $search_query }}">
                                                        <span class="input-group-text search"><i
                                                                class="las la-redo-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <form action="{{ route('admin.language.key.update', $language->id) }}" method="POST"
                                        class="translation_form">@csrf
                                        <div class="col-lg-12 staff-role-heigh simplebar">
                                            <div class="default-list-table table-responsive lang-setting">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">{{ __('key') }}</th>
                                                            <th scope="col" class="text-capitalize">
                                                                {{ config('app.locale') }}
                                                            </th>
                                                               
                                                            <th scope="col" class="text-capitalize">
                                                                {{ $language->locale }}
                                                            </th>
                                                            <th scope="col" class="text-capitalize">
                                                                {{ __('action') }}
                                                            </th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $counter = 0;
                                                        $totalTranslations = count($translations);
                                                        ?>
                                                        @foreach ($translations as $key => $item)
                                                            @isset($key)
                                                                @php
                                                                    $counter++;
                                                                @endphp
                                                                <tr id="item_{{ $key }}"
                                                                    class="{{ str_contains($key, $search_query) ? '' : 'd-none' }}">
                                                                    <td width="25%">
                                                                        {{ $key }}
                                                                    </td>
                                                                    <td width="30%">
                                                                        {{ __($key, [], 'en') }}
                                                                    </td>
                                                                    <td width="30%">
                                                                        <input type="hidden" name="keys[]"
                                                                            value="{{ $key }}">
                                                                        <input type="text"
                                                                            class="form-control rounded-2 translation_input"
                                                                            name="translations[]" value="{{ $item }}"
                                                                            placeholder="{{ __('enter_title') }}">
                                                                    </td>
                                                                    <td width="5%">
                                                                        <a title="{{ __('remove_keyword') }}"
                                                                            data-url="{{ route('admin.language.delete.key', $language->id) }}"
                                                                            href="javascript:void(0);"
                                                                            data-key="{{ $key }}"
                                                                            data-value="{{ $item }}"
                                                                            class="btn btn-sm btn-danger text-white {{ $language->locale !=='en' ? "__js_delete remove-lang-key":"disabled" }} ">
                                                                            <i class="la la-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endisset
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center mt-30">
                                            <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
                                            @include('backend.common.loading-btn', [
                                                'class' => 'btn sg-btn-primary',
                                            ])
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addKeywordModal" tabindex="-1" role="dialog" aria-labelledby="addKeywordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addKeywordModalLabel">{{ __('add_new') }}</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.language.store.key', $language->id) }}" id="store_lang_key_form"
                    method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="key">{{ __('key') }}</label>
                            <input type="text" class="form-control" id="key" name="key"
                                value="{{ old('key') }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="value">{{ __('value') }}</label>
                            <input type="text" class="form-control" id="value" name="value"
                                value="{{ old('value') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="mb-4 mt-2">
                            <div class="d-flex justify-content-end align-items-center mt-30">
                                <button id="preloader" class="btn btn-primary d-none" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <button type="submit" class="btn btn-primary save">{{ __('submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchAndReplaceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">{{ __('search_and_replace') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.language.replace.key') }}" id="search_replace_form" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $lang->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="key">{{ __('search_keyword') }}</label>
                            <input type="text" class="form-control" id="key" name="key"
                                value="{{ old('key') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="value">{{ __('replace_value') }}</label>
                            <input type="text" class="form-control" id="value" name="value"
                                value="{{ old('value') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end align-items-center mt-30">
                            <button id="preloader" class="btn btn-primary d-none" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="submit" class="btn btn-primary save">{{ __('replace') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
     
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="{{ static_asset('admin/js/custom/translation.js') }}?v=2.0.0"></script>
    <script>
        $(document).ready(function() {
            $(document).on('change', '.without_search', function() {
                $(this).closest('form').submit();
            });
            $(document).on('click', '.search', function() {
                if (!$('#inputGroup').val()) {
                    return false;
                }
                $(this).closest('form').submit();
            });
            $(document).on('click', '.search', function() {
                $(this).closest('form').submit();
            });
            $(document).on("submit", ".translation_form", function(e) {
                e.preventDefault();
                let selector = this;
                $(selector).find(".loading_button").removeClass("d-none");
                $(selector).find("p.error").text("");
                $(selector).find(":submit").addClass("d-none");
                let action = $(selector).attr("action");
                let method = $(selector).attr("method");
                let translations = $(selector).find("input[name^='translations']").serializeArray();
                let keys = $(selector).find("input[name^='keys']").serializeArray();
                $.ajax({
                    url: action,
                    method: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        keys: JSON.stringify(keys),
                        translations: JSON.stringify(translations)
                    },
                    success: function(response) {
                        if (response.success) {

                            $(selector).find(".loading_button").addClass("d-none");
                            $(selector).find(":submit").removeClass("d-none");
                            if (response.route) {
                                window.location.href = response.route;
                            } else {
                                location.reload();
                            }
                        } else {
                            $(selector).find(".loading_button").addClass("d-none");
                            $(selector).find(":submit").removeClass("d-none");
                            toastr.error(response.error);
                        }
                    },
                    error: function(response) {
                        $(selector).find(".loading_button").addClass("d-none");
                        $(selector).find(":submit").removeClass("d-none");
                        if (response.status == 422) {
                            if (formData.get("type") == "tab_form") {
                                instructorValidate(selector);
                            }
                            $.each(
                                response.responseJSON.errors,
                                function(key, value) {
                                    $("." + key + "_error").text(value[0]);
                                }
                            );
                        } else if (response.status == 403) {
                            toastr.error(response.status + ' ' + response.statusText);
                        } else {
                            toastr.error(response.responseJSON.message);
                        }
                    },
                });
            });

        });
    </script>
@endpush
{{-- remove-lang-key --}}
