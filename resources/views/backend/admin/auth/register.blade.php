<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ __('signup') }}</title>
    <!--====== LineAwesome ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/line-awesome.min.css') }}">
    <!--====== Dropzone CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
    <!--====== Summernote CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/summernote-lite.min.css') }}">
    <!--====== Choices CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/choices.min.css') }}">
    <!--====== AppCSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/app.css') }}">
    <!--====== ResponsiveCSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/toastr.min.css') }}">
</head>
<body>
    <section class="signup-section">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-8 col-md-8 col-sm-10 position-relative">

                    <img src="{{ static_asset('admin/img/shape/rect.svg') }}" alt="Rect Shape" class="bg-rect-shape">
                    <img src="{{ static_asset('admin/img/shape/circle.svg') }}" alt="Rect Shape"
                        class="bg-circle-shape">
                    <img src="{{ static_asset('admin/img/shape/circle-block.svg') }}" alt="Rect Shape"
                        class="bg-circle-block-shape">

                    <div class="login-form bg-white rounded-20">
                        <div class="logo d-flex justify-content-center items-center mb-5">
                            <a href="{{ url('/') }}">
                                <img style="max-height: 35px"
                                    src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80', []) }}"
                                    alt="Corporate Logo">
                            </a>
                        </div>
                        <h3>{{ __('sign_up') }}</h3>
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (Session::has('alert'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ Session::get('alert') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('signup.store') }}">
                            @csrf
                            <div class="row gx-20">
                                <div class="col-lg-6">
                                    <div class="mb-30 ">
                                        <label for="company_name" class="form-label">{{ __('company_name') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="first_name"
                                            value="{{ old('company_name') }}" name="company_name"
                                            placeholder="{{ __('company_name') }}" required autofocus>
                                        <x-input-error :messages="$errors->get('company_name')" class="mt-2 nk-block-des text-danger" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-30 ">
                                        <label for="first_name" class="form-label">{{ __('first_name') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="first_name"
                                            value="{{ old('first_name') }}" name="first_name"
                                            placeholder="{{ __('first_name') }}" required autofocus>
                                        <x-input-error :messages="$errors->get('first_name')" class="mt-2 nk-block-des text-danger" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-30 ">
                                        <label for="last_name" class="form-label">{{ __('last_name') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="last_name"
                                            value="{{ old('last_name') }}" name="last_name"
                                            placeholder="{{ __('last_name') }}" required autofocus>
                                        <x-input-error :messages="$errors->get('last_name')" class="mt-2 nk-block-des text-danger" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-30">
                                        <label for="email" class="form-label">{{ __('email') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control rounded-2" id="first_name"
                                            value="{{ old('email') }}" name="email"
                                            placeholder="{{ __('email') }}" required autofocus>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 nk-block-des text-danger" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-30">
                                        <label for="password" class="form-label">{{ __('password') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control rounded-2" id="password"
                                            placeholder="{{ __('password') }}" name="password" required autofocus>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 nk-block-des text-danger" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-30">
                                        <label for="password_confirmation"
                                            class="form-label">{{ __('confirm_password') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control rounded-2"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="{{ __('re_enter_password') }}" required autofocus>
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 nk-block-des text-danger" />
                                    </div>
                                </div>

                                @if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
                                    <div class="mb-30">
                                        <div id="html_element" class="g-recaptcha"
                                            data-sitekey="{{ setting('recaptcha_site_key') }}"></div>
                                    </div>
                                @endif

                                <div class="row justify-content-center">
                                    <div class="col-lg-6 mb-30">
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-lg sg-btn-primary">{{ __('sign_up') }}</button>
                                        </div>
                                    </div>
                                    <span class="text-center d-block">{{ __('already_have_an_account') }}?
                                        <a href="{{ route('login') }}"
                                            class="sg-text-primary">{{ __('login') }}</a> | <a
                                            href="{{ url('/') }}"
                                            class="sg-text-primary">{{ __('back_to_website') }}</a>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- JS Files -->
    <!--====== jQuery ======-->
    <script src="{{ static_asset('admin/js/jquery.min.js') }}"></script>
    <!--====== Bootstrap & Popper JS ======-->
    <script src="{{ static_asset('admin/js/bootstrap.bundle.min.js') }}"></script>
    <!--====== NiceScroll ======-->
    <script src="{{ static_asset('admin/js/jquery.nicescroll.min.js') }}"></script>
    <!--====== Bootstrap-Select JS ======-->
    <script src="{{ static_asset('admin/js/choices.min.js') }}"></script>
    <!--====== Summernote JS ======-->
    <script src="{{ static_asset('admin/js/summernote-lite.min.js') }}"></script>
    <!--====== Dropzone JS ======-->
    <!--====== ReCAPTCHA ======-->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="{{ static_asset('admin/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}
    @if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
        <script type="text/javascript">
            var onloadCallback = function() {
                grecaptcha.render('html_element', {
                    'sitekey': '{{ setting('recaptcha_site_key') }}',
                    'size': 'md'
                });
            };
        </script>
    @endif
</body>

</html>
