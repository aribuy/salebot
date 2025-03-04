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
                    <div class="logo d-flex justify-content-center items-center mb-4">
                        <a  href="{{url('/')}}">
                            <img style="max-height: 35px" src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80',[]) }}" alt="Corporate Logo">
                        </a>
                    </div>
                    <h3>{{__('forgot_password')}}</h3>
                    <p>{{__('enter_your_email_address_to_recover_your_password') }}</p>

                    <form method="POST" action="{{ route('forgot.password-email') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-30">
                            <label for="email" class="form-label">{{__('email')}} *</label>
                            <input type="text" class="form-control rounded-2" id="email" value="" name="email"  placeholder="{{ __('email') }}" required autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 nk-block-des text-danger" />
                        </div>
                        @if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
                            <div class="mb-30">
                                <div id="html_element" class="g-recaptcha" data-sitekey="{{setting('recaptcha_site_key')}}"></div>
                            </div>
                        @endif
                        <div class="mb-30"><button type="submit" class="btn btn-lg sg-btn-primary d-block w-100">{{__('submit')}}</button></div>
                        <span class="text-center d-block">{{__('back_to')}} <a href="{{ route('login') }}" class="sg-text-primary">{{__('sign_in')}}</a>?</span>
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
