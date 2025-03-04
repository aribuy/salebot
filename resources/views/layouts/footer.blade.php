 <!--====== Start Footer Area ======-->
<footer class="footer-area footer-area-v2">
    <div class="footer-widget">
        <div class="container container-1278">
            <div class="footer-top">
                <div class="row justify-content-between">
                    @if (setting('show_social_links') != 0)
                    <div class="col-md-6">
                        <div class="widget">
                            <h5 class="widget-title">{{__('connect_with_us!')}}</h5>
                            <ul class="social-profile-v2">
                                @if (setting('facebook_link') != '')
                                    <li><a href="{{ setting('facebook_link') }}"><i class="fab fa-facebook-f"></i></a></li>
                                @endif
                                @if (setting('twitter_link') != '')
                                    <li><a href="{{ setting('twitter_link') }}"><i class="fab fa-twitter"></i></a></li>
                                @endif
                                @if (setting('linkedin_link') != '')
                                    <li><a href="{{ setting('linkedin_link') }}"><i class="fab fa-linkedin-in"></i></a></li>
                                @endif
                                @if (setting('instagram_link') != '')
                                    <li><a href="{{ setting('instagram_link') }}"><i class="fab fa-instagram"></i></a></li>
                                @endif
                                @if (setting('youtube_link') != '')
                                    <li><a href="{{ setting('youtube_link') }}"><i class="fab fa-youtube"></i></a></li>
                                @endif

                            </ul>
                        </div>
                    </div>
                    @endif
                    @if (setting('show_newsletter') == 1)
                    <div class="col-md-auto">
                        <div class="widget newsletter-widget-v2">
                            <h5 class="widget-title">{{ setting('newsletter_title',app()->getLocale()) }}</h5>
                            <form action="{{ route('subscribe') }}" class="footer-subscription ajax_form" method="POST">@csrf
                                <input class="subscription-mail" type="email" name="email" placeholder="{{ __('email') }}">
                                <div class="nk-block-des text-danger">
                                    <p class="email_error error"></p>
                                </div>
                                <button type="submit">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.0818 0.0682323L1.05985 4.98194C0.763053 5.05991 0.49963 5.23066 0.308781 5.46877C0.117932 5.70687 0.00985741 5.99961 0.000642376 6.30341C-0.00857266 6.60722 0.081564 6.90585 0.257638 7.15486C0.433712 7.40387 0.686311 7.58995 0.977843 7.68541L8.24451 10.0954C8.271 10.1054 8.29523 10.1205 8.3158 10.1398C8.33637 10.159 8.35288 10.1822 8.36436 10.2078L11.0389 17.0758C11.1471 17.3517 11.3382 17.5881 11.5863 17.7533C11.8344 17.9186 12.1277 18.0046 12.4266 17.9998H12.4645C12.7685 17.9989 13.0645 17.903 13.3102 17.7259C13.556 17.5488 13.739 17.2994 13.8333 17.0133L18.9301 1.86639C19.01 1.62387 19.0216 1.3644 18.9639 1.11581C18.9061 0.86722 18.781 0.638895 18.602 0.455335C18.4107 0.256435 18.1667 0.114704 17.898 0.0462692C17.6292 -0.0221656 17.3464 -0.014556 17.0818 0.0682323Z" fill="white"/>
                                    </svg>
                                </button>
                                @include('components.frontend_loading_btn', ['class' => 'btn sg-btn-primary'])
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                @if (setting('show_useful_link') && count(setting('footer_useful_link_menu')) > 0)
                <div class="col-md-3 col-6 order-2 order-md-1">
                    <div class="widget nav-widget">
                        <h5 class="widget-title">{{ setting('useful_link_title',app()->getLocale()) }}</h5>
                        <ul>
                            @foreach ( headerFooterMenu('footer_useful_link_menu',app()->getLocale()) as $usefulLink)
                                <li><a href="{{ $usefulLink->url }}">{{ $usefulLink->label }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @if (setting('show_resource_link') && count(setting('footer_resource_link_menu')) > 0)
                <div class="col-md-3 col-6 order-3 order-md-2">
                    <div class="widget nav-widget">
                        <h5 class="widget-title">{{ setting('resource_link_title',app()->getLocale()) }}</h5>
                        <ul>
                            @foreach ( headerFooterMenu('footer_resource_link_menu',app()->getLocale()) as $resourceLink)

                               <li><a href="{{ $resourceLink['url'] }}">{{ $resourceLink['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @if (setting('show_quick_link') && count(setting('footer_quick_link_menu')) > 0)
                <div class="col-md-3 col-6 order-4 order-md-3">
                    <div class="widget nav-widget">
                        <h5 class="widget-title">{{ setting('quick_link_title',app()->getLocale()) }}</h5>
                        <ul>
                            @foreach ( headerFooterMenu('footer_quick_link_menu',app()->getLocale()) as $quickLink)
                               <li><a href="{{ $quickLink['url'] }}">{{ $quickLink['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @if (setting('show_apps_link') != 0)
                <div class="col-md-3 order-1 order-md-4">
                    <div class="widget download-app-widget">
                        <h5 class="widget-title">{{ setting('apps_link_title',app()->getLocale()) }}</h5>
                        <p>{{ setting('apps_link_description',app()->getLocale()) }}</p>
                        <div class="row gx-3 m-t-15">
                            @if (setting('play_store_link')!='')
                                <div class="col-xl-6 col-md-12 col-auto">
                                    <a href="{{setting('play_store_link')}}">
                                        <img src="{{ static_asset('frontend/img/store/google-play.png') }}" alt="Google Play">
                                    </a>
                                </div>
                            @endif

                            @if (setting('app_store_link')!='')
                                <div class="col-xl-6 col-md-12 col-auto">
                                    <a href="{{setting('app_store_link')}}">
                                        <img src="{{ static_asset('frontend/img/store/app-store.png') }}" alt="App Store">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="footer-bottom p-t-15 p-t-md-0">
                <div class="row justify-content-center align-items-center flex-column">
                    @if (setting('show_payment_method_banner') != 0)
                        <div class="col">
                            <div class="payment-logos text-align-md-end text-center">
                                <img src="{{ setting('payment_method_banner') && @is_file_exists(setting('payment_method_banner')['original_image']) ? get_media(setting('payment_method_banner')['original_image']) : get_media('frontend/img/payment-methods/footer-payment.png') }}" alt="Payment Logos">
                            </div>
                        </div>
                    @endif
                    @if (setting('show_copyright') != 0)
                    <div class="col-lg-auto col-md-5">
                        <div class="copyright-text d-flex align-items-end justify-content-center m-t-45">
                            <img src="{{ setting('copyright_logo') && @is_file_exists(setting('copyright_logo')['original_image']) ? get_media(setting('copyright_logo')['original_image']) : get_media('frontend/img/logo.png') }}" alt="Footer Logo" class="m-r-25">
                            <span>{{ setting('copyright_title',app()->getLocale()) }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
<!--====== End Footer Area ======-->

<!--====== Start Scroll To Top ======-->
<a href="#" class="back-to-top" id="fixed-scroll-top">
    <i class="far fa-angle-up"></i>
</a>

<!--====== Demo Switcher / Theme Options Modal ======-->
<div class="demo-switcher-modal">
    <div class="switcher-btn">
        <i class="far fa-cog"></i>
    </div>
    <div class="demo-switcher">
        <div class="demo-switcher-title">
            <h4>{{__('theme_customize')}}</h4>
        </div>
        <div class="demo-switcher-inner">
            <div class="demo-switcher-inner-content">
                <div class="color-switch m-b-30">
                    <h6 class="switcher-title">{{__('primary_color')}}</h6>
                    <input type="hidden" class="update_web_setting" value="<?php echo e(route('update.website-setting')); ?>">
                    <ul class="switcher-list">
                        <li>
                            <input class="changeWebSetting" type="radio" id="color1" name="primary_color" value="#4E9F3D" {{ (setting('primary_color') == '#4E9F3D') ? 'checked' : '' }}>
                            <label for="color1"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color2" name="primary_color" value="#7367F0" {{ (setting('primary_color') == '#7367F0') ? 'checked' : '' }} >
                            <label for="color2"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color3" name="primary_color" value="#FFB80E" {{ (setting('primary_color') == '#FFB80E') ? 'checked' : '' }}>
                            <label for="color3"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color4" name="primary_color" value="#F69EB1" {{ (setting('primary_color') == '#F69EB1') ? 'checked' : '' }}>
                            <label for="color4"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color5" name="primary_color" value="#57D0DE" {{ (setting('primary_color') == '#57D0DE') ? 'checked' : '' }}>
                            <label for="color5"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color6" name="primary_color" value="#FF6767" {{ (setting('primary_color') == '#FF6767') ? 'checked' : '' }}>
                            <label for="color6"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color7" name="primary_color" value="#3F497F" {{ (setting('primary_color') == '#3F497F') ? 'checked' : '' }}>
                            <label for="color7"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color8" name="primary_color" value="#F7941D" {{ (setting('primary_color') == '#F7941D') ? 'checked' : '' }}>
                            <label for="color8"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color9" name="primary_color" value="#D61D9A" {{ (setting('primary_color') == '#D61D9A') ? 'checked' : '' }}>
                            <label for="color9"></label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="color10" name="primary_color" value="#3E51D9" {{ (setting('primary_color') == '#3E51D9') ? 'checked' : '' }}>
                            <label for="color10"></label>
                        </li>
                    </ul>
                </div>
                <div class="header-switch m-b-30">
                    <h6 class="switcher-title">{{__('headers')}}</h6>
                    <ul class="switcher-list">
                        <li>
                            <input class="changeWebSetting" type="radio" id="header1" name="header" value="header_one" {{ setting('header') == 'header_one' ? 'checked' : '' }} >
                            <label for="header1">{{__('header_one')}}</label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="header2" name="header" value="header_two" {{ setting('header') == 'header_two' ? 'checked' : '' }} >
                            <label for="header2">{{__('header_two')}}</label>
                        </li>
                        <li>
                            <input class="changeWebSetting" type="radio" id="header3" name="header" value="header_three" {{ setting('header') == 'header_three' ? 'checked' : '' }}>
                            <label for="header3"> {{__('header_three')}}</label>
                        </li>
                    </ul>
                </div>
                <div class="footer-switch m-b-30">
                    <h6 class="switcher-title">{{__('footer')}}</h6>
                    <ul class="switcher-list">
                        <li>
                            <input class="changeWebSetting" type="radio" id="footer1" name="footer" value="footer_one" {{ setting('footer') == 'footer_one' ? 'checked' : '' }}>
                            <label for="footer1">{{__('footer_one')}}</label>
                        </li>
                    </ul>
                </div>
                <div class="dir-switch d-flex justify-content-between">
                    <h6 class="switcher-title m-0">{{__('rtl')}}</h6>
                    <div class="setting-check">
                        @php
                            $checked = '';
                             $current_url =  url()->current();
                             $url = explode('/', $current_url);
                             if(key_exists(4, $url)):
                                  if($url[4] == 'ar'){
                                       $checked = 'checked';
                                  }
                             endif;
                        @endphp
                        <input class="changeWebLanguage" type="checkbox" name="language" id="language" value="ar" data-url="<?php  echo ($checked ? URL::to("/en") : URL::to("/ar")); ?>" {{ $checked }}>
                        <label for="language"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<h1>

</h1>
@php  $lang =  app()->getLocale() @endphp
@if(((\Request::route()->getName() == 'home' && setting('popup_show_in') == 'home_page') || setting('popup_show_in') == 'all_page') && setting('site_popup_status') == 1)
<!--====== Window Load SubscriptionMiddleWare Modal ======-->
@if(!session()->get('dont_show'))
<div class="modal window-load-modal fade" id="windowLoadModal" tabindex="-1" aria-labelledby="windowLoadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-xl-center justify-content-center">
                    <div class="col-lg-6">
                        <div class="modal-thumbnail m-b-md-20">

                            <img class="selected-img" src="{{  getFileLink('500x500',setting('popup_image')) }}" alt="favicon">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="modal-content-inner">
                            <h4>{{ setting('popup_title',$lang) }}</h4>
                            <p>{{ setting('popup_description', $lang) }}</p>
                            <form action="{{ route('subscribe') }}" class="footer-subscription ajax_form" method="POST">@csrf
                                <input class="subscription-mail" type="email" name="email" placeholder="{{ __('email') }}">
                                <div class="nk-block-des text-danger">
                                    <p class="email_error error"></p>
                                </div>
                                    <button name="submit" type="submit" class="template-btn">
                                        {{__('subscribe')}}
                                    </button>
                                    @include('components.frontend_loading_btn', ['class' => 'template-btn'])

                                <div class="social-links">
                                    <ul>
                                        @if (setting('facebook_link') != '')
                                            <li><a href="{{ setting('facebook_link') }}"><i class="fab fa-facebook-f"></i></a></li>
                                        @endif
                                        @if (setting('twitter_link') != '')
                                            <li><a href="{{ setting('twitter_link') }}"><i class="fab fa-twitter"></i></a></li>
                                        @endif
                                        @if (setting('linkedin_link') != '')
                                            <li><a href="{{ setting('linkedin_link') }}"><i class="fab fa-linkedin-in"></i></a></li>
                                        @endif
                                        @if (setting('instagram_link') != '')
                                            <li><a href="{{ setting('instagram_link') }}"><i class="fab fa-instagram"></i></a></li>
                                        @endif
                                        @if (setting('youtube_link') != '')

                                        @endif
                                            <li><a href="{{ setting('youtube_link') }}">{{ session()->get('dont_show') }}</a></li>
                                    </ul>
                                </div>
                                <div class="dont-show-popup">
                                    <form class="form-checkbox">
                                        <div class="form-group">
                                            <input type="checkbox" id="tnc" value="1" class="form-check-input" name="dont_show_this">
                                            <label for="tnc">{{__('dont_show_this_again')}} </label>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif
<!--======= Cookie Alert Popup =======-->
<div class="cookiealert-popup">
    <div class="container container-1278">
        <div class="row">
            <div class="col-12">
                <div class="cookiealert-content text-center">
                    <h4>{{__('What do we use cookies for?')}}</h4>
                    <p>{{__('We use essential cookies to make our site work. With your consent, we may also use non-essential cookies to improve user experience and analyze website traffic. By clicking “Accept,” you agree to our website’s cookie use as described in our')}} <a href="#">{{__('cookie_policy')}}</a>{{__('You can change your cookie settings at any time ny clicking')}} “<a href="#">{{__('preferences')}}</a>.”</p>
                    <div class="confirmation-btns d-flex justify-content-center">
                        <button type="button" class="dont-accept-cookies template-btn">{{__('dont_accept')}}</button>
                        <button type="button" class="accept-cookies template-btn">{{__('accept')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

