@extends('backend.layouts.master')
@section('title', __('inbox'))
@push('css')
    <link rel="stylesheet" href="{{ static_asset('client/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ static_asset('client/css/vue-plyr.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ static_asset('client/css/emoji.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/template.css') }}?v={{ time() }}">
<style>
    .modal-mask {
      position: fixed;
      z-index: 9998;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
    }
    .modal-wrapper {
			margin: 0 15px;
		}
		.modal-container {
			width: 100%;
			max-width: 500px;
      /* margin: 150px auto; */
      padding: 20px 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);

      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    @media (max-width: 575px) {
			.modal-container {
				padding: 20px;

			}
		}
    .modal-header{
      border-bottom: 1px solid #e9e9e9;
      margin-bottom: 10px;
      font-size: 16px;
      font-weight: 500;
      line-height: 19px;
      color: #556068;
      padding-bottom: 10px;
    }
    .modal-body{
      /* max-height: 500px; */
      overflow-x: hidden;
      overflow-y: auto;
      width: 100%;
      max-height: 380px;
    }
    </style>
@endpush
@php
    $manifest_file = public_path('client/js/build/manifest.json');
    $manifest = file_exists($manifest_file) ? json_decode(file_get_contents($manifest_file), true) : ['resources/js/app.js' => ['file' => 'app.js']];
    $js = $manifest['resources/js/app.js']['file'];
    $parse_url = parse_url(config('app.url'));
    $path = '/';

    if(!empty($parse_url['path'])){
        $path = trim($parse_url['path'], '/');
    }
@endphp
@section('content')
    <div id="app"></div>
    <input type="hidden" value="{{ url('/') }}" id="base_url">
    <input type="hidden" value="{{ static_asset('/') }}" id="asset_url">
    <input type="hidden" value="{{ setting('is_pusher_notification_active') }}" id="is_pusher_active">
    <input type="hidden" value="{{ setting('pusher_app_key') }}" id="f_pusher_app_key">
    <input type="hidden" value="{{ setting('pusher_app_cluster') }}" id="f_pusher_app_cluster">
    <input type="hidden" value="{{ json_encode(auth()->user()->client) }}" id="auth_user">
    <input type="hidden" value="{{ json_encode($contact) }}" id="contact">
    <input type="hidden" id="app_path" value="{{ $path }}">
@endsection
@push('js')
<script>
    // window.translations = {!! json_encode(json_decode(file_get_contents(base_path('lang/en.json')), true)) !!};
  <?php 
  // Determine the locale, defaulting to 'en' if not set
  $locale = systemLanguage() ? systemLanguage()->locale : 'en';
  // Path to the language file
  $langFilePath = base_path("lang/{$locale}.json");
  // Load the language file's contents
  $translations = file_exists($langFilePath) ? json_decode(file_get_contents($langFilePath), true) : [];
  // Encode the translations to JSON and pass to JavaScript
  ?>
  window.translations = {!! json_encode($translations) !!};
  </script>
    @if(file_exists($manifest_file))
        {{ Vite::useBuildDirectory('client/js/build') }}
        <script src="{{ static_asset("client/js/build/$js") }}?v={{ time() }}"></script>
    @else
        @vite(['resources/js/app.js', 'resources/css/app.css'])
    @endif
@endpush
