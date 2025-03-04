<!-- Start Advantage Card Area -->
<div class="dreamd-advantage-area bg-white dreamd-section-gap-big">
    <div class="container">
        <div class="row row--15">
            <div class="col-lg-12">
                <div class="section-title text-center sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                    <h3 class="title mb--20">{{ setting('advantage_section_title', app()->getLocale()) }}</h3>
                    <p class="description b1 mw-100">{{ setting('advantage_section_subtitle', app()->getLocale()) }}</p>
                </div>
            </div>
        </div>

        <div class="row row--15 mt_dec--30 service-wrapper">
            @foreach($advantages as $index=>$advantage)
                @if($index % 2 == 0)
                    <div class="col-lg-7 col-12 mt--30 sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                        <div class="advantage-card-box">
                            <div class="inner">
                                <div class="content">
                                    <h5 class="title">{{ @$advantage->language->title}}</h5>
                                    <p class="description">{!! @$advantage->language->description!!}</p>
                                </div>
                                <div class="image">
                                    <img src="{{ static_asset(@$advantage->image['original_image'])}}" alt="card Images">
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-5 col-12 mt--30 sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                        <div class="advantage-card-box sm-box">
                            <div class="inner">
                                <div class="content">
                                    <h5 class="title">{{ @$advantage->language->title}}</h5>
                                    <p class="description">{!! @$advantage->language->description!!}</p>
                                </div>
                                <div class="image">
                                    <img src="{{ static_asset(@$advantage->image['original_image'])}}" alt="card Images">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
<!-- End Advantage Card Area -->