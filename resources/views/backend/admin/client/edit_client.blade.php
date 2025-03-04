@extends('backend.layouts.master')
@section('title', __('clients'))
@section('content')
    @push('css_asset')
        <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
    @endpush
    <section>
        <div class="container-fluid d-flex justify-content-center">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-title">{{ __('edit_client') }}</h3>
                    <form action="{{ route('clients.update', $client->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="bg-white redious-border p-20 p-sm-30">
                            <h6 class="sub-title">{{ __('client_information') }}</h6>
                            <div class="row gx-20">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="organisationName" class="form-label">{{ __('company_name') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="company_name"
                                            name="company_name" value="{{ old('company_name', $client->company_name) }}"
                                            placeholder="{{ __('company_name') }}" required>
                                        @if ($errors->has('company_name'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('company_name') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="select-type-v2 mb-4 list-space">
                                        <label for="country" class="form-label">{{ __('country') }}<span
                                                class="text-danger">*</span></label>
                                        <div class="select-type-v1 list-space">
                                            <select class="form-select form-select-lg rounded-0 mb-3 with_search"
                                                aria-label=".form-select-lg example" name="country_id" required>
                                                <option value="" selected>{{ __('select_country') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        {{ $country->id == old('country_id', $client->country_id) ? 'selected' : '' }}>
                                                        {{ __($country->name) }}</option>
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
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="address" class="form-label">{{ __('address_line') }}</label>
                                        <input type="text" class="form-control rounded-2" id="address" name="address"
                                            value="{{ old('email', $client->address) }}" placeholder="{{ __('address') }}">
                                        @if ($errors->has('address'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('address') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 input_file_div">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">{{ __('logo') }}</label>
                                        <label for="logo" class="file-upload-text">
                                            <p></p><span class="file-btn">{{ __('choose_file') }}</span>
                                        </label>
                                        <input class="d-none file_picker" type="file" id="logo" name="logo"
                                            accept=".jpg,.png">
                                        <div class="nk-block-des text-danger">
                                            <p class="logo_error error">{{ $errors->first('logo') }}</p>
                                        </div>
                                    </div>
                                    <div class="selected-files d-flex flex-wrap gap-20">
                                        <div class="selected-files-item">
                                            <img class="selected-img" src="{{ getFileLink('80x80', $client->logo) }}"
                                                alt="favicon">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-30">
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

    @include('backend.common.gallery-modal')
    </div>
@endsection
@push('js')
    <script src="{{ static_asset('admin/js/countries.js') }}"></script>
@endpush
