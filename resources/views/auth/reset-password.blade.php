<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{__('login')}}</title>
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
            <div class="col-lg-5 col-md-8 col-sm-10 position-relative">

                <img src="{{ static_asset('admin/img/shape/rect.svg') }}" alt="Rect Shape" class="bg-rect-shape">
                <img src="{{ static_asset('admin/img/shape/circle.svg') }}" alt="Rect Shape" class="bg-circle-shape">
                <img src="{{ static_asset('admin/img/shape/circle-block.svg') }}" alt="Rect Shape" class="bg-circle-block-shape">

                <div class="login-form bg-white rounded-20">
                    <div class="logo">
                        <a href="{{ url('/') }}" class="">
                            <img class="logo mx-auto d-block mb-30" src="{{ static_asset('website/images/logo/logo.png')}}" alt="Corporate Logo">
                        </a>
                    </div>
                    <h3>{{__('confirm_your_password') }}</h3>
                    <form method="POST" action="{{ route('user.password-update') }}" class="form needs-validation" novalidate>
                        @csrf
                        {{--                            <input type="hidden" name="otp" value="{{ $otp_request->otp }}" required>--}}
                        {{--                            <input type="hidden" name="email" value="{{ $otp_request->user->email }}" required>--}}
                        <div class="row">
                            <div class="col-12 password-input">
                                <label for="password">{{__('password') }} *</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Choose Password" data-lpignore="true" required>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 nk-block-des text-danger" />
                                <div class="invalid-feedback">
                                    {{__('please_add_a_password') }}.
                                </div>
                                <div class="valid-feedback">
                                    {{__('looks_good') }}!
                                </div>
                                <span id="#password" class="fa fa-fw fa-eye toggle-password"></span>
                                @if($errors->has('password'))
                                    <div class="nk-block-des text-danger">
                                        <p>{{ $errors->first('password') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 password-input mt-2">
                                <label for="password_confirmation">{{__('confirm_password') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Choose Password" data-lpignore="true" required>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 nk-block-des text-danger" />
                                <div class="invalid-feedback">
                                    {{__('please_add_a_confirm_password') }}.
                                </div>
                                <div class="valid-feedback">
                                    {{__('looks_good') }}!
                                </div>
                                <span id="#password_confirmation" class="fa fa-fw fa-eye toggle-password"></span>
                                @if($errors->has('password_confirmation'))
                                    <div class="nk-block-des text-danger">
                                        <p>{{ $errors->first('password_confirmation') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mb-30 mt-20"><button type="submit" class="btn btn-lg sg-btn-primary d-block w-100">{{__('submit')}}</button></div>
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
<!--====== MainJS ======-->
<script src="{{ static_asset('admin/js/app.js') }}"></script>

<script src="{{ static_asset('admin/js/toastr.min.js') }}"></script>
{!! Toastr::message() !!}
@if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
    <script type="text/javascript">
        var onloadCallback = function() {
            grecaptcha.render('html_element', {
                'sitekey' : '{{setting('recaptcha_site_key')}}',
                'size' : 'md'
            });
        };
    </script>
@endif
</body>
</html>

