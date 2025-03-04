@extends('backend.layouts.master')
@section('title', __('contacts'))
@section('content')
    <div class="main-content-wrapper">
        <section class="oftions">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h3 class="section-title">{{ __('edit_contacts') }}</h3>
                        <form action="{{ route('client.contact.update', $contact->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="bg-white redious-border p-20 p-sm-30">
                                <div class="row gx-20 add-coupon">
                                    <input type="hidden" class="is_modal" value="0" />
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="name" class="form-label">{{ __('name') }}<span
                                                class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="name"
                                                name="name" value="{{ old('name', $contact->name) }}"
                                                placeholder="{{ __('name') }}" required>
                                            @if ($errors->has('name'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('name') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="phone" class="form-label">{{ __('phone') }}
                                                <small>({{ __('with_country_phonecode') }})</small><span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="phone"
                                                name="phone" value="{{ old('phone', $contact->phone) }}"
                                                placeholder="{{ __('phone') }}">
                                            @if ($errors->has('phone'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('phone') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <div class="select-type-v2">
                                                <label for="contact_list_id"
                                                    class="form-label">{{ __('contact_lists') }}</label>
                                                <select id="contact_list_id" name="contact_list_id[]"
                                                    class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
                                                    aria-label=".form-select-lg example" multiple="multiple">
                                                    @foreach ($lists as $list)
                                                        <option value="{{ $list->id }}"
                                                            {{ in_array($list->id, $contact->contactList->pluck('contact_list_id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $list->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="nk-block-des text-danger">
                                                    <p class="error">{{ $errors->first('contact_list_id') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <div class="select-type-v2">
                                                <label for="select_segments"
                                                    class="form-label">{{ __('segments') }}</label>
                                                <select id="select_segments" name="segments[]"
                                                    class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
                                                    aria-label=".form-select-lg example" multiple="multiple">
                                                    @foreach ($segments as $segment)
                                                        <option value="{{ $segment->id }}"
                                                            {{ in_array($segment->id, $contact->segmentList->pluck('segment_id')->toArray()) ? 'selected' : '' }}
                                                            >
                                                            {{ $segment->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="nk-block-des text-danger">
                                                    <p class="error">{{ $errors->first('segments') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="country_id" class="form-label">{{ __('country') }}</label>
                                            <div class="select-type-v1 list-space">
                                                <select
                                                    class="form-select form-select-lg rounded-0 mb-3 with_search filterable"
                                                    aria-label=".form-select-lg example" id="country_id" name="country_id"
                                                    style="width: 100%">
                                                    <option value="" selected>{{ __('select_country') }}</option>
                                                    @foreach ($countries as $key => $country)
                                                        <option value="{{ $key }}"
                                                            {{ old('country_id', $contact->country_id) == $key ? 'selected' : '' }}>
                                                            {{ __($country) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('country_id'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('country_id') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 input_file_div mb-4">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">{{ __('contact_photo') }}</label>
                                            <label for="images" class="file-upload-text">
                                                <p></p>
                                                <span class="file-btn">{{ __('choose_file') }}</span>
                                            </label>
                                            <input class="d-none file_picker" type="file" id="images" name="images">
                                            <div class="nk-block-des text-danger">
                                                <p class="image_error error">{{ $errors->first('images') }}</p>
                                            </div>
                                        </div>
                                        <div class="selected-files d-flex flex-wrap gap-20">
                                            <div class="selected-files-item">
                                                <img class="selected-img"
                                                    src="{{ getFileLink('80x80', $contact->images) }}" alt="favicon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mt-30">
                                        <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
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
@push('js')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush
