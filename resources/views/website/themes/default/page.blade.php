@extends('website.themes.'.active_theme().'.master')
@section('content')
    <div class="cns-banner-area banner-style-1">
        @include('website.themes.'.active_theme().'.header')
    </div>
    <div class="cns-brand-area bg-white ptb--50 border--bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb--0">
                        <h5 class="title mb--32 mt-0">{!! $page_info->title !!}</h5>
                    </div>
                </div>
                <div class="col-lg-12">
                    {!! $page_info->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection