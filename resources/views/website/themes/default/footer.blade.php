<!-- Start Footer Area  -->
<footer class="dreamd-footer footer-style-default no-border">
    <div class="container">
        <div class="footer-top">
            <div class="container">
                <div class="row justify-content-between">

                    <div class="col-lg-4 order-3 mt-5 mt-lg-0 order-md-1">
                        <div class="dreamd-footer-widget">
                            <div class="widget-menu-top mb--70 mb_sm--50">
                                <h3 class="title">{!! setting('high_lighted_text',app()->getLocale()) !!}</h3>
                                <ul class="contact-list">
                                    <li>
                                        <i class="las la-envelope"></i>
                                        <a href="mailto:{!! setting('contact_email',app()->getLocale()) !!}">{!! setting('contact_email',app()->getLocale()) !!}</a>
                                    </li>
                                    <li>
                                        <i class="las la-phone"></i>
                                        <a href="tel:{!! setting('contact_phone',app()->getLocale()) !!}">{!! setting('contact_phone',app()->getLocale()) !!}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mt-lg-0 order-1 order-md-2">
                        <div class="dreamd-footer-widget">
                            <div class="inner">
                                @if($menu_useful_links && is_array(setting('footer_useful_link_menu')))
                                @foreach($menu_useful_links as $key => $value)
                                    <ul class="footer-link link-hover">
                                        <li><a href="{{ @$value['url'] == 'javascript:void(0)' ? '#' : @$value['url'] }}">{{ @$value['label'] }}</a></li>
                                    </ul>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 mt-5 mt-lg-0 order-2 order-md-3">
                        <div class="dreamd-footer-widget">
                            <div class="inner">
                                @if($menu_quick_links && is_array(setting('footer_useful_link_menu')))
                                    @foreach($menu_quick_links as $key => $value)
                                        <ul class="footer-link link-hover">
                                            <li><a href="{{ @$value['url'] == 'javascript:void(0)' ? '#' : @$value['url'] }}">{{ @$value['label'] }}</a></li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="footerBottom-area dreamd-footerBottom-default">
            <div class="container">
                <div class="row row--0 footerBottom-wrapper align-items-center border-top-bottom">
                    <div class="col-lg-4">

                        <div class="logo">
                            <a href="#">
                                <img class="logo-light" src="{{ setting('copyright_logo') && @is_file_exists(setting('copyright_logo')['original_image']) ? get_media(setting('copyright_logo')['original_image']) : getFileLink('80x80',[]) }}"
                                     alt="Corporate Logo">
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <ul class="brand-list">
                            <li><a href="#"><img src="{{ setting('payment_method_banner') && @is_file_exists(setting('payment_method_banner')['original_image']) ? get_media(setting('payment_method_banner')['original_image']) : getFileLink('80x80',[]) }}" alt="Footer Brand"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="copyright-area">
            <div class="container">
                <div class="row align-items-center copyright-wrapper">
                    <div class="col-12">
                        <div class="copyright text-center">
                            <p class="copyright-text mb--0 link-hover">{!! setting('copyright_title',app()->getLocale()) !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer Area  -->