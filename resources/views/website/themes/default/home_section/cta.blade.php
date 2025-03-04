@if(setting('cta_enable') == 1)
    <div class="dd-call-to-action-area dreamd-section-gap pb_lg--0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="dd-cta">
                        <div class="section-title mb--0 text-center">
                            <h2 class="title">{!! setting('cta_title', app()->getLocale()) !!}
                            </h2>
                            <a class="btn-default round has-right-icon btn-large color-green" href="{!! setting('cta_main_action_btn_url', app()->getLocale()) !!}">
                                {!! setting('cta_main_action_btn_label', app()->getLocale()) !!}
                                <i class="las la-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
