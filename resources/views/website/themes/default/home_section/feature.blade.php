<!-- Start Split Tab Area -->
<div class="split-tab-area dreamd-section-gap"  id="features">
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb_sm--30 text-center sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="150">
                    <h6 class="subtitle round">
                        Features 
                    </h6>
                    <h3 class="title mb--0">{!! setting('feature_section_title',app()->getLocale()) !!}
                </div>

                <nav class="spa-tab">
                    <div class="tab-btn-grp nav nav-tabs mb-3 text-center justify-content-center" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-whatsup-tab" data-bs-toggle="tab" data-bs-target="#nav-whatsup" type="button" role="tab" aria-controls="nav-whatsup" aria-selected="true">
                            <i class="lab la-whatsapp"></i> {{__('whatsapp')}} <span class="d-none d-lg-block">{{__('features')}}</span>
                        </button>
                        <button class="nav-link with-badge" id="nav-telegram-tab" data-bs-toggle="tab" data-bs-target="#nav-telegram" type="button" role="tab" aria-controls="nav-telegram" aria-selected="false">
                            <i class="lab la-telegram"></i> {{__('telegram')}} <span class="d-none d-lg-block">{{__('features')}}</span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Content Part -->
        <div class="wrapper">
            <div class="tab-content p-0 bg-transparent border-0 border bg-light" id="nav-tabContent-one">
                <div class="tab-pane fade active show" id="nav-whatsup" role="tabpanel" aria-labelledby="nav-whatsup-tab">
                    @if(!is_null($whatsapp_features))
                        @foreach(@$whatsapp_features as $index => $whatsapp_feature)
                            <div class="row mt_dec--30 align-items-center dreamd-section-gapTop row--40">
                                @if($index % 2 == 0)
                                    <div class="col-lg-12 col-xl-6 col-12 mt--30 order-2 order-lg-1">
                                        <div class="thumbnail image-left-content">
                                            <img src="{{  getFileLink('original_image', $whatsapp_feature->image) }}" alt="split Images">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-6 col-12 mt--30 order-1 order-lg-2">
                                        <div class="split-inner">
                                            <h4 class="title sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="200" style="font-family: Inter, Bangla1062, sans-serif;">{{ @$whatsapp_feature->language->title, app()->getLocale() }}</h4>
                                            <ul class="split-list sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="350">
                                                @if(!is_null($whatsapp_feature->language))
                                                    @foreach(@$whatsapp_feature->language->description as $description)
                                                        <li><i class="las la-check-circle"></i> 
                                                            {!! $description !!}
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                <div class="row mt_dec--30 align-items-center dreamd-section-gapTop row--40">
                                    <div class="col-lg-12 col-xl-6 col-12 mt--30">
                                        <div class="split-inner">
                                            <h4 class="title sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="200" style="font-family: Inter, Bangla1062, sans-serif;">{{ @$whatsapp_feature->language->title, app()->getLocale() }}</h4>
                                            <ul class="split-list sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="350">
                                                @if(!is_null($whatsapp_feature->language))
                                                    @foreach(@$whatsapp_feature->language->description as $description)
                                                        <li><i class="las la-check-circle"></i> 
                                                            {!! $description !!}
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-6 col-12 mt--30">
                                        <div class="thumbnail image-left-content">
                                            <img src="{{  getFileLink('original_image', $whatsapp_feature->image) }}" alt="split Images">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="tab-pane fade" id="nav-telegram" role="tabpanel" aria-labelledby="nav-telegram-tab">
                    @if(!is_null($telegram_features))
                        @foreach($telegram_features as $index => $telegram_feature)
                            <div class="row mt_dec--30 align-items-center dreamd-section-gapTop row--40">
                                @if($index % 2 == 0)
                                <div class="col-lg-12 col-xl-6 col-12 mt--30 order-2 order-lg-1">
                                    <div class="thumbnail image-left-content">
                                        <img src="{{  getFileLink('original_image', $telegram_feature->image) }}" alt="split Images">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6 col-12 mt--30 order-1 order-lg-2">
                                    <div class="split-inner">
                                        <h4 class="title sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="200" style="font-family: Inter, Bangla1062, sans-serif;">{{ @$telegram_feature->language->title, app()->getLocale() }}</h4>
                                        <ul class="split-list sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="350">
                                            @if(!is_null($telegram_feature->language))
                                                @foreach(@$telegram_feature->language->description as $description)
                                                    <li><i class="las la-check-circle"></i> {!! $description!!} </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12 col-xl-6 col-12 mt--30">
                                    <div class="split-inner">
                                        <h4 class="title sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="200" style="font-family: Inter, Bangla1062, sans-serif;">{{ @$telegram_feature->language->title, app()->getLocale() }}</h4>
                                        <ul class="split-list sal-animate" data-sal="slide-up" data-sal-duration="400" data-sal-delay="350">
                                            @if(!is_null($telegram_feature->language))
                                                @foreach(@$telegram_feature->language->description as $description)
                                                    <li><i class="las la-check-circle"></i> {!! $description !!} </li>
                                                @endforeach 
                                            @endif                                   
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6 col-12 mt--30">
                                    <div class="thumbnail image-left-content">
                                        <img src="{{  getFileLink('original_image', $telegram_feature->image) }}" alt="split Images">
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
<!-- End Split Tab Area -->