<!DOCTYPE html>
<html lang="{{ systemLanguage() ? systemLanguage()->locale : 'en' }}"
    dir="{{ systemLanguage() ? systemLanguage()->text_direction : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Error</title>

    <!--====== Favicon ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/bootstrap.min.css') }}">
    <!--====== Slick Slider ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/slick.min.css') }}">
    <!--====== Magnific ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/magnific-popup.min.css') }}">
    <!--====== Nice Select ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/select2.min.css') }}">
    <!--====== Nice Select ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/nice-select.min.css') }}">
    <!--====== Plyr CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/plyr.css') }}">
    <!--====== Font Awesome ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/fonts/fontawesome/css/all.min.css') }}">
    <!--====== Box Icons ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/fonts/boxicons/css/boxicons.min.css') }}">
    <!--====== Spacing CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/spacing.min.css') }}">
    <!--====== AOS CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/aos.css') }}">
    <!--====== Main CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/style.css') }}">
    <!--====== Responsive CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/responsive.css') }}">
    <!--====== Color CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('frontend/css/theme/olive.css') }}">

    <link rel="stylesheet" href="{{ static_asset('frontend/css/toastr.min.css') }}">

</head>
<body>

@yield('base.content')


<!--====== jQuery ======-->
<script src="{{ static_asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
<!--====== Popper JS ======-->
<script src="{{ static_asset('frontend/js/popper.min.js') }}"></script>
<!--====== Bootstrap ======-->
<script src="{{ static_asset('frontend/js/bootstrap.min.js') }}"></script>
<!--====== Slick Slider ======-->
<script src="{{ static_asset('frontend/js/slick.min.js') }}"></script>
<!--====== Magnific ======-->
<script src="{{ static_asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
<!--====== Plyr JS ======-->
<script src="{{ static_asset('frontend/js/plyr.js') }}"></script>
<!--====== Nice Select ======-->
<script src="{{ static_asset('frontend/js/jquery.nice-select.min.js') }}"></script>
<!--====== Nice Select ======-->
<script src="{{ static_asset('frontend/js/select2.min.js') }}"></script>
<!--====== AOS JS ======-->
<script src="{{ static_asset('frontend/js/aos.js') }}"></script>
<!--====== Cookie Alert ======-->
<script src="{{ static_asset('frontend/js/cookiealert.js') }}"></script>
<!--====== Main JS ======-->
<script src="{{ static_asset('frontend/js/main.js') }}"></script>
<!--====== App JS ======-->
<script src="{{ static_asset('frontend/js/app.js') }}"></script>

</body>
</html>
