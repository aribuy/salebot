<!-- Start Banner Area  -->
<div class="cns-banner-area banner-style-1">
    @include('website.themes.'.active_theme().'.header')
    <div class="container">
        <div class="row row--15 mt_dec--30 align-items-center">
            <div class="col-lg-6 mt--30">
                <div class="banner-content">
                    <h1 class="title">
                        {!! setting('hero_title',app()->getLocale()) !!}
                    </h1>
                    <p class="description">
                        {!! setting('hero_description',app()->getLocale()) !!}
                    </p>
                    <div class="banner-btn">
                        @if(setting('hero_main_action_btn_enable') == '1')
                            <a class="btn-default round has-right-icon btn-large" href="{{setting('hero_main_action_btn_url',app()->getLocale())}}">{{setting('hero_main_action_btn_label',app()->getLocale())}} <i class="las la-angle-right"></i></a>
                        @endif
                        @if(setting('hero_secondary_action_btn_enable') == '1')
                            <a class="btn-link-dflt" href="{{setting('hero_secondary_action_btn_url',app()->getLocale())}}">{{setting('hero_secondary_action_btn_label',app()->getLocale())}}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt--30">
                <div class="banner-thumbnail">
                    <div class="inner">
                        <img src="{{  getFileLink('80x80',setting('header1_hero_image1')) }}" alt="Hero images">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Banner Area  -->