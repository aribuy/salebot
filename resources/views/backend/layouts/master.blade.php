@extends('backend.layouts.base')
@section('base.content')
    @if (Auth::check() && auth()->user()->role_id == 3)
        @include('backend.client.partials.sidebar')
    @else
        @include('backend.layouts.sidebar')
    @endif
    <main class="main-wrapper">
        @if (Auth::check() && auth()->user()->role_id == 3)
            @include('backend.client.partials.header')
        @else
            @include('backend.layouts.header')
        @endif
        <div class="main-content-wrapper">
            @yield('content')
        </div>
    </main>
    @include('backend.layouts.footer')
@endsection
