<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ __('login') }}</title>
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
    <link rel="stylesheet" href="{{ static_asset('admin/css/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ static_asset('admin/css/auth.css') }}">

</head>

<body>
    <section class="signup-section">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-6 col-md-9 col-sm-12 position-relative">

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
                        <h3>{{ __('login_to_your_account') }}</h3>
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ Session::get('success') }}
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (Session::has('alert'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ Session::get('alert') }}
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ Session::get('error') }}
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form class="ajax_form" method="POST" action="{{ route('postlogin') }}">
                            @csrf
                            <div class="mb-30">
                                <label for="email" class="form-label">{{ __('email') }} *</label>
                                <input type="text" class="form-control rounded-2" id="email"
                                    value="{{ old('email') }}" name="email" placeholder="{{ __('email') }}"
                                    required autofocus>
                                <x-input-error :messages="$errors->get('email')" class="mt-2 nk-block-des text-danger" />
                            </div>
                            <div class="mb-30">
                                <label for="password" class="form-label">{{ __('password') }} *</label>
                                <input type="password" class="form-control rounded-2" id="password"
                                    placeholder="{{ __('password') }}" name="password" required autofocus>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 nk-block-des text-danger" />
                            </div>
                            @if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
                                <div class="mb-30">
                                    <div id="html_element" class="g-recaptcha"
                                        data-sitekey="{{ setting('recaptcha_site_key') }}"></div>
                                </div>
                            @endif
                            <div class="custom-checkbox mb-30">
                                <label>
                                    <input type="checkbox" id="remember_me" name="remember" value="1">
                                    <span>{{ __('stay_logged_in') }}</span>
                                </label>
                            </div>
                            <div class="mb-30"><button type="submit"
                                    class="btn btn-lg sg-btn-primary d-block w-100">{{ __('login') }}</button></div>
                            <span class="text-center d-block">{{ __("doesn't_have_an_account_yet?") }} <a
                                    href="{{ route('register') }}"
                                    class="sg-text-primary">{{ __('sign_up') }}</a>?</span>
                            @if (Route::has('password.request'))
                                <span class="text-center d-block mb-20">{{ __('forgot_your') }} <a
                                        href="{{ route('password.request') }}"
                                        class="sg-text-primary">{{ __('password') }}</a>?</span>
                            @endif
                            @if (isDemoMode())
                                <div class="login-as">
                                    <h6>{{ __('login_as') }}</h6>
                                    <ul class="login-BTN d-flex gap-3">
                                        <li>
                                            <a href="javascript:void(0)"
                                                class="input_filler template-btn bordered-btn-secondary"
                                                data-type="admin">{{ __('admin') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)"
                                                class="input_filler template-btn bordered-btn-secondary"
                                                data-type="client">{{ __('client') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
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
    <script src="{{ static_asset('admin/js/toastr.min.js') }}"></script>
    <!--====== ReCAPTCHA ======-->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

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

    <script>
        $(document).ready(function() {
            $(document).on('click', '.login-as a', function() {
                var type = $(this).data('type');
                if (type == 'admin') {
                    $('#email').val('admin@spagreen.net');
                    $('#password').val('123456');
                } else if (type == 'client') {
                    $('#email').val('client1@spagreen.net');
                    $('#password').val('123456');
                }
                $('.ajax_form').submit();
            });
        });
    </script>

</body>

</html>
