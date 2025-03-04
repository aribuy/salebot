<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="contactModalLabel">{{ __('add_new_contacts') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client.contact.store') }}" id="__contact_modal_form" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <div class="row gx-20 add-coupon">
                            <input type="hidden" class="is_modal" value="0" />
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ __('name') }}</label>
                                    <input type="text" class="form-control rounded-2" id="name" name="name"
                                        placeholder="{{ __('name') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="phone" class="form-label">{{ __('phone') }}
                                        <small>({{ __('with_country_phonecode') }})</small><span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-2" id="phone" name="phone"
                                        placeholder="{{ __('phone') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <div class="select-type-v2">
                                        <label for="segment_id" class="form-label">{{ __('segments') }}<span
                                                class="text-danger">*</span></label>
                                        <select id="segment_id" name="segment_id[]"
                                            class="form-select rounded-0 mb-3 without_search"
                                            aria-label=".form-select-lg example" multiple="multiple">
                                            @foreach ($segments as $segment)
                                                <option value="{{ $segment->id }}">{{ $segment->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">{{ __('country') }}</label>
                                    <select class="form-select rounded-0 mb-3 with_search"
                                        aria-label=".form-select-lg example" id="country_id" name="country_id"
                                        style="width: 100%">
                                        <option value="" selected>{{ __('select_country') }}</option>
                                        @foreach ($countries as $key => $country)
                                            <option value="{{ $key }}"
                                                {{ old('country_id') == $key ? 'selected' : '' }}>{{ __($country) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-30">
                                <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
                                @include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
