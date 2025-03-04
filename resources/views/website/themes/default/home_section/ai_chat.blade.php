<!-- Start Call to Action Area -->
<div class="dd-cta-area dreamd-section-gap-big bg-white">
    <div class="container">
        <div class="cta-style-one">
            <div class="row row--15 mt_dec--30 align-items-center">
                <div class="col-lg-6 mt--30">
                    <div class="content">
                        <h2 class="title">{!! setting('ai_title',app()->getLocale()) !!}</h2>
                        <p class="description">{!! setting('ai_description', app()->getLocale()) !!}</p>
                        <a class="btn-default round has-right-icon btn-large color-green" href="{{ setting('ai_main_action_btn_url') }}">{{ setting('ai_main_action_btn_label', $lang) }} <i class="las la-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-5 mt--30 offset-lg-1">
                    <div class="image">
                        <img src="{{ static_asset('website/images/cta/cta-img-01.png')}}" alt="Call To Action Image">
                        <img class="qr-code" src="{{  getFileLink('928x954',setting('qr_image')) }}" alt="QR iMAGE">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
