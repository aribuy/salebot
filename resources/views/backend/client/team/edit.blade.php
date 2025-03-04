@extends('backend.layouts.master')
@section('title', __('team'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{ __('update_client_team') }}</h3>

                </div>
                <div class="default-tab-list table-responsive default-tab-list-v2 activeItem-bd-md bg-white redious-border p-20 p-sm-30">
                    <div class="default-list-table yajra-dataTable">
                        <form class="form" action="{{ route('client.team.update',$user->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="basicInfo" role="tabpanel"
                                     aria-labelledby="basicInformation" tabindex="0">
                                    <input type="hidden" name="type" value="tab_form">
                                    <div class="row gx-20">
                                        <div class="col-lg-3 col-md-4">
                                            <div class="mb-4">
                                                <label for="firstName" class="form-label">{{ __('first_name') }}<span
                                                            class="text-danger">*</span></label>
                                                <input type="text" class="form-control rounded-2" id="firstName"
                                                       name="first_name" value="{{ $user->first_name }}"
                                                       placeholder="{{ __('enter_first_name') }}">
                                                <div class="nk-block-des text-danger">
                                                    <p class="first_name_error error">{{ $errors->first('first_name') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="mb-4">
                                                <label for="lastName" class="form-label">{{ __('last_name') }}<span
                                                            class="text-danger">*</span></label>
                                                <input type="text" class="form-control rounded-2" id="lastName"
                                                       name="last_name" value="{{ $user->last_name }}"
                                                       placeholder="{{ __('enter_last_name') }}">
                                                <div class="nk-block-des text-danger">
                                                    <p class="last_name_error error">{{ $errors->first('last_name') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            @include('backend.common.tel-input', [
												'name' => 'phone',
												'value' => $user->phone,
												'label' => __('phone_number'),
												'id' => 'phoneNumber',
												'country_id_field' => 'phone_country_id',
												'country_id' =>
													old('phone_country_id') ?: (setting('default_country') ?: 19),
											])
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="mb-4">
                                                <label for="emailAddress" class="form-label">{{ __('email_address') }}
                                                    <span
                                                            class="text-danger">*</span></label>
                                                <input type="email" class="form-control rounded-2" id="emailAddress"
                                                       name="email" value="{{ old('email',$user->email) }}"
                                                       placeholder="{{ __('enter_email_address') }}" autocomplete="off">
                                                <div class="nk-block-des text-danger">
                                                    <p class="email_error error">{{ $errors->first('email') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="mb-4">
                                                <label for="password" class="form-label">{{ __('password') }}</label>
                                                <input type="password" class="form-control rounded-2" id="password"
                                                       name="password" placeholder="{{ __('enter_password') }}">
                                                <div class="nk-block-des text-danger">
                                                    <p class="password_error error">{{ $errors->first('password') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="mb-4">
                                                <label for="confirm_password"
                                                       class="form-label">{{ __('confirm_password') }}</label>
                                                <input type="password" class="form-control rounded-2"
                                                       id="confirm_password"
                                                       name="password_confirmation"
                                                       placeholder="{{ __('re_enter_password') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 input_file_div mb-4">
                                            <div class="mb-3">
                                                <label class="form-label mb-1">{{ __('upload_profile_photo') }}</label>
                                                <label for="profilePhoto" class="file-upload-text">
                                                    <p></p>
                                                    <span class="file-btn">{{ __('choose_file') }}</span>
                                                </label>
                                                <input class="d-none file_picker" type="file" id="profilePhoto"
                                                       name="image" accept=".jpg,.png">
                                                <div class="nk-block-des text-danger">
                                                    <p class="image_error error">{{ $errors->first('image') }}</p>
                                                </div>
                                            </div>
                                            <div class="selected-files d-flex flex-wrap gap-20">
                                                <div class="selected-files-item">
                                                    <img class="selected-img" src="{{ getFileLink('80x80', $user->images) }}"
                                                         alt="favicon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <h6 class="sub-title mb-3">{{ __('permissions') }}</h6>
                                        </div>

                                        @foreach (config('static_array.client_permissions') as $key=> $permission)
                                        @if (!($key == 'manage_widget') || ($key == 'manage_widget' && addon_is_activated('chat_widget')))
                                        <div class="col-lg-3 col-md-4">
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="{{ $key }}">{{ __($permission) }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[{{ $key }}]" type="checkbox"
                                                           id="{{ $key }}"
                                                           value="1" {{ is_array($user->permissions) && in_array($key, $user->permissions) ? 'checked' : '' }}>
                                                    <label for="{{ $key }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        {{-- <div class="col-lg-3 col-md-4">
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox5">{{ __('whatsapp') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_whatsapp]" type="checkbox"
                                                           id="checkbox5"
                                                           value="1" {{ is_array($user->permissions) && in_array('manage_whatsapp', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox5"></label>
                                                </div>
                                            </div>
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox6">{{ __('telegram') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_telegram]" type="checkbox"
                                                           id="checkbox6"  value="1" {{ is_array($user->permissions) && in_array('manage_telegram', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox6"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox7">{{ __('ai_writer') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_ai_writer]" type="checkbox"
                                                           id="checkbox7"  value="1" {{ is_array($user->permissions) && in_array('manage_ai_writer', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox7"></label>
                                                </div>
                                            </div>
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox8">{{ __('team') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_team]" type="checkbox"
                                                           id="checkbox8"
                                                           value="1" {{ is_array($user->permissions) && in_array('manage_team', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox8"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox9">{{ __('chat') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_chat]" type="checkbox"
                                                           id="checkbox9"
                                                           value="1" {{ is_array($user->permissions) && in_array('manage_chat', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox9"></label>
                                                </div>
                                            </div>
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox10">{{ __('setting') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_setting]" type="checkbox"
                                                           id="checkbox10"
                                                           value="1" {{ is_array($user->permissions) && in_array('manage_setting', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox10"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox11">{{ __('campaigns') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_campaigns]" type="checkbox"
                                                           id="checkbox11"
                                                           value="1" {{ is_array($user->permissions) && in_array('manage_campaigns', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox11"></label>
                                                </div>
                                            </div>
                                            <div class="price-checkbox d-flex gap-12 mb-4">
                                                <label for="checkbox12">{{ __('ticket') }}</label>
                                                <div class="setting-check">
                                                    <input name="permissions[manage_ticket]" type="checkbox"
                                                           id="checkbox12"
                                                           value="1" {{ is_array($user->permissions) && in_array('manage_ticket', $user->permissions) ? 'checked' : '' }}>
                                                    <label for="checkbox12"></label>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mt-30">
                                        <button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
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
        </div>
    </div>
    @include('backend.common.delete-script')
    @include('backend.common.change-status-script')
    @push('js')

        <script src="{{ static_asset('admin/js/countries.js') }}"></script>
    @endpush
@endsection
