<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- SEO -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="title" content="{{ setting('meta_title')}}"/>
        <meta name="description" content="{{ setting('meta_description')}}"/>
        <meta name="keywords" content="{{ setting('meta_keywords')}}"/>
        <meta name="author" content="{{ setting('author_name')}}">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <!-- END SEO -->

        <!-- Open Graph -->
        <meta property="og:title" content="{{ setting('og_title')}}"/>
        <meta property="og:description" content="{{ setting('meta_description')}}"/>
        <meta property="og:url" content="{{ url('/')}}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="{{ app()->getLocale() }}"/>
        <meta property="og:site_name" content="{{ setting('system_name') }}"/>
        <meta property="og:image" content="{{ getFileLink('original_image',setting('og_image')) }}"/>
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <!-- END Open Graph -->

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="{{ setting('system_name') }}" />
        <meta name="twitter:creator" content="{{ setting('author_name')}}" />
        <meta name="twitter:title" content="{{ setting('meta_title')}}" />
        <meta name="twitter:description" content="{{ setting('meta_description')}}" />
        <meta name="twitter:image" content="{{ getFileLink('original_image',setting('og_image')) }}" />
        <!-- END Card -->
        @if(setting('meta_title') != '')
            <title>{{setting('meta_title')}}</title>
        @else
            <title>@yield('title',setting('system_name'))</title>
        @endif

        @php
            $icon = setting('favicon');
        @endphp

        @if ($icon)
            <link rel="apple-touch-icon" sizes="57x57"
                  href="{{ $icon != [] && @is_file_exists($icon['image_57x57_url']) ? static_asset($icon['image_57x57_url']) : static_asset('images/default/favicon/favicon-57x57.png') }}">
            <link rel="apple-touch-icon" sizes="60x60"
                  href="{{ $icon != [] && @is_file_exists($icon['image_60x60_url']) ? static_asset($icon['image_60x60_url']) : static_asset('images/default/favicon/favicon-60x60.png') }}">
            <link rel="apple-touch-icon" sizes="72x72"
                  href="{{ $icon != [] && @is_file_exists($icon['image_72x72_url']) ? static_asset($icon['image_72x72_url']) : static_asset('images/default/favicon/favicon-72x72.png') }}">
            <link rel="apple-touch-icon" sizes="76x76"
                  href="{{ $icon != [] && @is_file_exists($icon['image_76x76_url']) ? static_asset($icon['image_76x76_url']) : static_asset('images/default/favicon/favicon-76x76.png') }}">
            <link rel="apple-touch-icon" sizes="114x114"
                  href="{{ $icon != [] && @is_file_exists($icon['image_114x114_url']) ? static_asset($icon['image_114x114_url']) : static_asset('images/default/favicon/favicon-114x114.png') }}">
            <link rel="apple-touch-icon" sizes="120x120"
                  href="{{ $icon != [] && @is_file_exists($icon['image_120x120_url']) ? static_asset($icon['image_120x120_url']) : static_asset('images/default/favicon/favicon-120x120.png') }}">
            <link rel="apple-touch-icon" sizes="144x144"
                  href="{{ $icon != [] && @is_file_exists($icon['image_144x144_url']) ? static_asset($icon['image_144x144_url']) : static_asset('images/default/favicon/favicon-144x144.png') }}">
            <link rel="apple-touch-icon" sizes="152x152"
                  href="{{ $icon != [] && @is_file_exists($icon['image_152x152_url']) ? static_asset($icon['image_152x152_url']) : static_asset('images/default/favicon/favicon-152x152.png') }}">
            <link rel="apple-touch-icon" sizes="180x180"
                  href="{{ $icon != [] && @is_file_exists($icon['image_180x180_url']) ? static_asset($icon['image_180x180_url']) : static_asset('images/default/favicon/favicon-180x180.png') }}">
            <link rel="icon" type="image/png" sizes="192x192"
                  href="{{ $icon != [] && @is_file_exists($icon['image_192x192_url']) ? static_asset($icon['image_192x192_url']) : static_asset('images/favicon-192x192.png') }}">
            <link rel="icon" type="image/png" sizes="32x32"
                  href="{{ $icon != [] && @is_file_exists($icon['image_32x32_url']) ? static_asset($icon['image_32x32_url']) : static_asset('images/default/favicon/favicon-32x32.png') }}">
            <link rel="icon" type="image/png" sizes="96x96"
                  href="{{ $icon != [] && @is_file_exists($icon['image_96x96_url']) ? static_asset($icon['image_96x96_url']) : static_asset('images/default/favicon/favicon-96x96.png') }}">
            <link rel="icon" type="image/png" sizes="16x16"
                  href="{{ $icon != [] && @is_file_exists($icon['image_16x16_url']) ? static_asset($icon['image_16x16_url']) : static_asset('images/default/favicon/favicon-16x16.png') }}">
            <link rel="manifest" href="{{ static_asset('images/default/favicon/manifest.json') }}">

            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage"
                  content="{{ $icon != [] && @is_file_exists($icon['image_144x144_url']) ? static_asset($icon['image_144x144_url']) : static_asset('images/default/favicon/favicon-144x144.png') }}">
        @else
            <link rel="shortcut icon" href="{{ static_asset('images/default/favicon/favicon-96x96.png') }}">
        @endif

        <style>
            @if (base64_decode(setting('custom_css')))
                {{ base64_decode(setting('custom_css')) }}
            @endif
        </style>

        @if (setting('is_google_analytics_activated') && setting('tracking_code'))
            {!! base64_decode(setting('tracking_code')) !!}
        @endif
        @if (setting('custom_header_script'))
            {!! base64_decode(setting('custom_header_script')) !!}
        @endif
        @if (setting('is_facebook_pixel_activated') && setting('facebook_pixel_id'))
            {!! base64_decode(setting('facebook_pixel_id')) !!}
        @endif

        <!-- All CSS Here
        ============================================ -->
        <link rel="stylesheet" href="{{ static_asset('website/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ static_asset('website/css/line-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ static_asset('website/css/slick.css')}}">
        <link rel="stylesheet" href="{{ static_asset('website/css/slick-theme.css')}}">
        <link rel="stylesheet" href="{{ static_asset('website/css/style.css')}}">
        {!! font_link() !!}
        <style>
            :root {
                --body-font: '{{ setting("body_font") }}', sans-serif;
                --header-font: '{{ setting("header_font") }}', sans-serif;
            }
        </style>
    </head>
    <body>
    <main class="page-wrapper">
        @yield('content')
        @include('website.themes.default.footer')
    </main>
    <div class="dreamd-back-top">
        <i class="las la-arrow-up"></i>
    </div>

    <!-- JS
    ============================================ -->
    <script src="{{ static_asset('website/js/vendor/jquery.min.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/popper.min.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/bootstrap.min.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/waypoint.min.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/slick.min.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/jquery-one-page-nav.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/counterup.min.js')}}"></script>
    <script src="{{ static_asset('website/js/vendor/sal.min.js')}}"></script>
    <script src="{{ static_asset('website/js/main.js')}}"></script>
    <script src="http://127.0.0.1:8000/chat-widget.js?id=CW85641646891"></script>

    @if (setting('is_pusher_notification_active') && auth()->check())
        <script src="{{ static_asset('admin/js/pusher.min.js') }}"></script>
        <script>
            const pusher = new Pusher('{{ setting('pusher_app_key') }}', {
                cluster: '{{ setting('pusher_app_cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe('notification-send-{{ auth()->id() }}');
            channel.bind('App\\Events\\PusherNotification', (data) => {
                toastr[data.message_type](data.message);
            });
        </script>
    @endif
    <script>
        //facebook chat
        @if (setting('is_tawk_messenger_activated') == 1)

        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/{{ setting('tawk_property_id') }}/{{ setting('tawk_widget_id') }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
        @endif

                @if (setting('is_facebook_messenger_activated') == 1)
            window.fbAsyncInit = function () {
            FB.init({
                appId: 'facebook-developer-app-id',

                autoLogAppEvents: true,
                xfbml: true,
                version: 'v3.3'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        @endif

                @if (auth()->check() && auth()->user()->role_id > 1)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ static_asset('admin/js/OneSignalSDKWorker.js') }}')
                .then(function (registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function (error) {
                    console.error('Service Worker registration failed:', error);
                });
        }
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function () {
            OneSignal.init({
                appId: "{{ setting('onesignal_app_id') }}",
                safari_web_id: "{{ setting('safari_web_id') }}",
                notifyButton: {
                    enable: true,
                },
                serviceWorker: {
                    path: "{{ static_asset('admin/js/OneSignalSDKWorker.js') }}",
                },
                allowLocalhostAsSecureOrigin: true,

            });
            OneSignal.on('subscriptionChange', function (isSubscribed) {
                if (isSubscribed) {
                    OneSignal.getUserId().then(function (userId) {
                        $.ajax({
                            url: '{{ route('onesignal.update-subscription') }}',
                            method: 'POST',
                            data: {
                                player_id: userId,
                                subscribed: 1
                            }
                        });
                    });
                }
                else{
                    $.ajax({
                        url: '{{ route('onesignal.update-subscription') }}',
                        method: 'POST',
                        data: {
                            subscribed: 0
                        }
                    });
                }
            });
        });
        @endif
    </script>
    @if (setting('custom_footer_script'))
        {!! base64_decode(setting('custom_footer_script')) !!}
    @endif

    </body>
</html>