<!doctype html>
<html lang="{{ systemLanguage() ? systemLanguage()->locale : 'en' }}" dir="{{ systemLanguage() ? systemLanguage()->text_direction : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <base href="{{ url('/') }}">
    <title> @yield('title')
        | {{ setting('system_title') != '' ? setting('system_title') : setting('system_name') }}</title>
    @php
        $icon = setting('admin_favicon');
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
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage"
              content="{{ $icon != [] && @is_file_exists($icon['image_144x144_url']) ? static_asset($icon['image_144x144_url']) : static_asset('images/default/favicon/favicon-144x144.png') }}">
    @else
        <link rel="apple-touch-icon" sizes="57x57"
              href="{{ static_asset('images/default/favicon/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60"
              href="{{ static_asset('images/default/favicon/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72"
              href="{{ static_asset('images/default/favicon/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76"
              href="{{ static_asset('images/default/favicon/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114"
              href="{{ static_asset('images/default/favicon/apple-icon-114x114.png')}}">
        <link rel="apple-touch-icon" sizes="120x120"
              href="{{ static_asset('images/default/favicon/apple-icon-120x120.png')}}">
        <link rel="apple-touch-icon" sizes="144x144"
              href="{{ static_asset('images/default/favicon/apple-icon-144x144.png')}}">
        <link rel="apple-touch-icon" sizes="152x152"
              href="{{ static_asset('images/default/favicon/apple-icon-152x152.png')}}">
        <link rel="apple-touch-icon" sizes="180x180"
              href="{{ static_asset('images/default/favicon/apple-icon-180x180.png')}}">
        <link rel="icon" type="image/png" sizes="192x192"
              href="{{ static_asset('images/default/favicon/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32"
              href="{{ static_asset('images/default/favicon/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96"
              href="{{ static_asset('images/default/favicon/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16"
              href="{{ static_asset('images/default/favicon/favicon-16x16.png')}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ static_asset('images/default/favicon/ms-icon-144x144.png')}}">
        <meta name="theme-color" content="#ffffff">
    @endif
    <!-- CSS Files -->
    <!--====== LineAwesome ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/line-awesome.min.css') }}">
    <!--====== select2 CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/select2.min.css') }}">
    <!--====== Nestable CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/nestable.css') }}">
    <!--====== Summernote CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/summernote-lite.min.css') }}">
    <!--====== datatable ======-->
    <link href="{{ static_asset('admin/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!--====== AppCSS ======-->
    @stack('css_asset')
    <link rel="stylesheet" href="{{ static_asset('admin/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/app.css') }}?v=1.0.0">
    <link rel="stylesheet" href="{{ static_asset('admin/css/responsive.min.css') }}">
    @stack('css')
    <style>
        .user-info-panel .badge {
            font-size: 12px;
            line-height: 16px;
            font-weight: 500;
            padding: 5px 10px;
            margin-left: 20px;
        }
    </style>
    <style>
      .chat_sidebar_collapse .main-wrapper {
            padding-left: 80px;
      }
      .chat_sidebar_collapse .header-position {
            width: 80px ;
      }
      .chat_sidebar_collapse .dashboard-logo .logo {
            display: none;
      }
      .chat_sidebar_collapse .dashboard-logo .logo-icon {
            display: block;
      }
      .chat_sidebar_collapse .header-position .side-nav {
            padding: 0 15px;
      }
      .chat_sidebar_collapse .side-nav ul li a span {
            display: none;
      }
      .chat_sidebar_collapse .side-nav ul li a.dropdown-icon::after {
            display: none;
      }
      .chat_sidebar_collapse .side-nav ul li a {
            padding: 12px 14px;
      }
      @media (max-width: 991px) {
            .main-wrapper {
                  padding-left: 0 !important;
            }
            .navbar-top {
                  min-height: 60px;
                  /* padding: 0 !important; */
            }
            .sp-left-sidebar {
                  top: 66px;
            }
            .chatpage-wrapper .sp-chat-main-wrapper {
                  height: calc(100vh - 66px);
            }
      }
      .chat_sidebar_collapse.sidebar-collapse .main-wrapper {
            padding-left: 312px;
      }
      .chat_sidebar_collapse.sidebar-collapse .header-position {
            width: 312px;
      }
      .chat_sidebar_collapse.sidebar-collapse .dashboard-logo .logo {
            display: block;
      }
      .chat_sidebar_collapse.sidebar-collapse .dashboard-logo .logo-icon {
            display: none;
      }
      .chat_sidebar_collapse.sidebar-collapse .header-position .side-nav {
            padding: 0 15px;
      }
      .chat_sidebar_collapse.sidebar-collapse .side-nav ul li a span {
            display: block;
      }
      .chat_sidebar_collapse.sidebar-collapse .side-nav ul li a.dropdown-icon::after {
            display: block;
      }
      .chat_sidebar_collapse.sidebar-collapse .side-nav ul li a {
            /* padding: 12px 14px; */
      }
</style>
</head>
<body class="{{ request()->routeIs('client.chat.index') || request()->is('client/flow-builders/*') ? 'chat_sidebar_collapse ' : '' }}">
<input type="hidden" class="base_url" value="{{ url('/') }}">
@yield('base.content')
<script src="{{ static_asset('admin/js/jquery.min.js') }}"></script>
<!--====== Bootstrap & Popper JS ======-->
<script src="{{ static_asset('admin/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ static_asset('admin/js/popper.min.js') }}"></script>
<script src="{{ static_asset('admin/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ static_asset('admin/js/dataTables.responsive.min.js') }}"></script>
<!--====== NiceScroll ======-->
<script src="{{ static_asset('admin/js/jquery.nicescroll.min.js') }}"></script>
<!--====== Summernote JS ======-->
<script src="{{ static_asset('admin/js/summernote-lite.min.js') }}"></script>
<!--====== select2 JS ======-->
<script src="{{ static_asset('admin/js/select2.min.js') }}"></script>
<!--====== Chart JS ======-->
<script src="{{ static_asset('admin/js/chart.min.js') }}"></script>
<!--====== datatable ======-->
<!--====== MainJS ======-->
<script src="{{ static_asset('admin/js/app.js?ver=1.0.0') }}"></script>
<script src="{{ static_asset('admin/js/toastr.min.js') }}"></script>
{!! Toastr::message() !!}
<script src="{{ static_asset('admin/js/sweetalert211.min.js') }}"></script>
@if (auth()->check() && setting('is_onesignal_active'))
    <script src="{{ static_asset('client/js/OneSignalSDK.js') }}" defer></script>
@endif
@if (setting('is_pusher_notification_active') && auth()->check())
    <script src="{{ static_asset('admin/js/pusher.min.js') }}"></script>
    <script>
      // Initialize Pusher
      @if (env('APP_DEBUG'))
         Pusher.logToConsole = true;
      @endif
        const pusher = new Pusher('{{ setting('pusher_app_key') }}', {
            cluster: '{{ setting('pusher_app_cluster') }}',
            encrypted: true
        });
        // Subscribe to channels
        const channel1 = pusher.subscribe('test-channel-{{ auth()->id() }}');
        channel1.bind('App\\Events\\TestPusherEvent', function(data) {
            Swal.fire({
                title: 'Pusher Notification',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
        const channel2 = pusher.subscribe('notification-send-{{ auth()->id() }}');
        channel2.bind('App\\Events\\PusherNotification', (data) => {
            toastr[data.message_type](data.message);
        });
    </script>
@endif
@stack('js_asset')
@stack('js')
@if (auth()->check() && setting('is_onesignal_active'))
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(function (OneSignal) {
            OneSignal.init({
                appId: "{{ setting('onesignal_app_id') }}",
                serviceWorker: {
                    path: "/OneSignalSDKWorker.js",
                },
                notifyButton: {
                    enable: false,
                },
                allowLocalhostAsSecureOrigin: true,

            });
            // console.log(event);
            OneSignal.User.PushSubscription.addEventListener("change", pushSubscriptionChangeListener);
        });
        function pushSubscriptionChangeListener(event) {
            console.log(event);
            if (event.current.token) {
                let url = '{{ auth()->user()->user_type == 'admin' ? route('admin.onesignal') : route('client.onesignal') }}'
                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    onesignal_token: event
                });
            }
        }
    </script>    
@endif
@if (session()->has('danger'))
    <script>
        toastr.error("{{ session('danger') }}")
    </script>
@endif
@if (session()->has('success'))
    <script>
        toastr.success("{{ session('success') }}")
    </script>
@endif
</body>
</html>