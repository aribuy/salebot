<div class="row">
    <div class="col-12">
        <div class="mb-4 mt-2 position-relative" id="button_wrapper">
            @include('backend.client.whatsapp.template.partials._add_button_action')

            <div class="append-button" id="append-button">
                @if (isset($buttons) && !empty($buttons))
                    @foreach ($buttons as $key => $button)
                        @switch($button['type'])
                            @case('URL')
                                <div class="card mt-2 c-card" data-action="visit_website" id="{{ $key }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
                                                <div class="type_of_action">
                                                    <label for="type_of_action" class="d-block">{{ __('type_of_action') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select name="type_of_action[]" id="type_of_action" class="form-select"
                                                        required>
                                                        <option value="URL">{{ __('visit_website') }}
                                                        </option>
                                                    </select>
                                                    <div class="invalid-feedback text-danger">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label for="button_text" class="d-block">{{ __('btn_text') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control button_text_input"
                                                    name="button_text[]" placeholder="{{ __('enter_button_text') }}"
                                                    maxlength="20" value="{{ $button['text'] }}" required>
                                                <div class="invalid-feedback text-danger">
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <label for="website_url" class="d-block">{{ __('website_url') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="url" class="form-control" name="button_value[]"
                                                    placeholder="{{ __('enter_website_url') }}" maxlength="2000"
                                                    value="{{ $button['url'] }}" required>
                                                <div class="invalid-feedback text-danger">
                                                </div>
                                            </div>
                                            <div class="col-1 text-end mt-4">
                                                <button type="button" class="btn btn-danger text-white remove-card"><i
                                                        class="las la-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @case('PHONE_NUMBER')
                                <div class="card mt-2 c-card" data-action="call_phone_number" id="{{ $key }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
                                                <div class="type_of_action">
                                                    <label for="type_of_action" class="d-block">{{ __('type_of_action') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select name="type_of_action[]" id="type_of_action" class="form-select"
                                                        required>
                                                        <option value="PHONE_NUMBER">{{ __('call_phone_number') }}</option>
                                                    </select>
                                                    <div class="invalid-feedback text-danger">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label for="button_text" class="d-block">{{ __('btn_text') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control button_text_input"
                                                    name="button_text[]" placeholder="{{ __('enter_button_text') }}"
                                                    maxlength="20" value="{{ $button['text'] }}" required>
                                                <div class="invalid-feedback text-danger">
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <label for="phone_number" class="d-block">{{ __('phone_number') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="button_value[]"
                                                    placeholder="{{ __('enter_phone_number') }}" maxlength="20"
                                                    value="{{ $button['phone_number'] }}" required>
                                                <div class="invalid-feedback text-danger">
                                                </div>
                                            </div>
                                            <div class="col-1 text-end mt-4">
                                                <button type="button" class="btn btn-danger text-white remove-card"><i
                                                        class="las la-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @case('QUICK_REPLY')
                                <div class="card mt-2 c-card" id="{{ $key }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
                                                <div class="type_of_action">
                                                    <input type="hidden" class="form-control button_text_input"
                                                        name="button_value[]" placeholder="Enter {{ __('btn_text') }}">
                                                    <label for="type_of_action"
                                                        class="d-block sr-only">{{ __('type_of_action') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select name="type_of_action[]" id="type_of_action" class="form-select"
                                                        required hidden>
                                                        <option value="QUICK_REPLY">
                                                            {{ __('quick_reply') }}
                                                        </option>
                                                    </select>
                                                    <div class="invalid-feedback text-danger">
                                                    </div>

                                                    <label for="button_text" class="d-block">{{ __('btn_text') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control button_text_input"
                                                        name="button_text[]" placeholder="{{ __('enter_button_text') }}"
                                                        value="{{ $button['text'] }}" required>
                                                </div>
                                            </div>
                                            <div class="col-1 text-end mt-4">
                                                <button type="button" class="btn btn-danger text-white remove-card"><i
                                                        class="las la-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @case('OTP')
                                <div class="card mt-2 c-card" id="{{ $key }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="type_of_action" class="d-block">{{ __('type_of_action') }}<span
                                                        class="text-danger">*</span></label>
                                                <select name="type_of_action[]" id="type_of_action" class="form-select">
                                                    <option value="COPY_CODE">
                                                        {{ __('copy_offer_code') }}
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback text-danger">
                                                </div>
                                                <input type="hidden" class="form-control button_text_input"
                                                    name="button_text[]" placeholder="{{ __('enter_button_text') }}"
                                                    maxlength="20">
                                            </div>
                                            <div class="col-3">
                                                <label for="button_text" class="d-block">{{ __('btn_text') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control button_text_input"
                                                    name="button_value[]" placeholder="{{ __('enter_button_text') }}"
                                                    value="{{ $button['text'] }}">
                                                <div class="invalid-feedback text-danger">
                                                </div>
                                            </div>
                                            <div class="col-1 text-end mt-4 ">
                                                <button type="button" class="btn btn-danger text-white remove-card"><i
                                                        class="las la-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break
                        @endswitch
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
