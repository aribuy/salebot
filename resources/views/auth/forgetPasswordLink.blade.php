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
</head>
<body>
    <section class="signup-section">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-5 col-md-8 col-sm-10 position-relative">
                    <img src="{{ static_asset('admin/img/shape/rect.svg') }}" alt="Rect Shape" class="bg-rect-shape">
                    <img src="{{ static_asset('admin/img/shape/circle.svg') }}" alt="Rect Shape"
                        class="bg-circle-shape">
                    <img src="{{ static_asset('admin/img/shape/circle-block.svg') }}" alt="Rect Shape"
                        class="bg-circle-block-shape">
                    <div class="login-form bg-white rounded-20">
                        <div class="logo d-flex justify-content-center items-center mb-4">
                            <a href="{{ url('/') }}">
                                <img style="max-height: 35px"
                                    src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80', []) }}"
                                    alt="Corporate Logo">
                            </a>
                        </div>
                        <form action="{{ route('reset-password.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="mb-30">
                                <label for="email_address" class="form-label">{{__('email')}} *</label>
                                <input type="text" id="email_address" class="form-control" name="email" required
                                    autofocus>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="mb-30">
                                <label for="password" class="form-label">{{__('password')}} *</label>
                                <input type="password" id="password" class="form-control" name="password" required
                                    autofocus>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="mb-30">
                                <label for="password-confirm" class="form-label">{{__('confirm_password')}} *</label>
                                <input type="password" id="password-confirm" class="form-control"
                                    name="password_confirmation" required autofocus>
                                @if ($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                            <div class="mb-30">
                                <button type="submit" class="btn btn-lg sg-btn-primary d-block w-100">
                                    {{__('reset_password')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
