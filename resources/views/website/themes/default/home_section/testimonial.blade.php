<!-- Start Testimonial Area -->
<div class="dd-about-section bg-white" id="testimonials">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                    <h3 class="title mb--20">{!! setting('testimonial_section_title',app()->getLocale()) !!}</h3>
                    <p class="description">{!! setting('testimonial_section_subtitle',app()->getLocale()) !!}</p>
                </div>
            </div>
        </div>

        <div class="row row--15 mt_dec--30 plr--100 testimonial-wrapper">
            @foreach($testimonials as $testimonial)

            <div class="col-xl-3 col-lg-4 mt--30">
                    <div class="testimonial-card-one">
                        <div class="inner">
                            <ul class="review-icon">
                                @switch($testimonial->rating)
                                    @case('5')
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        @break
                                    @case('4')
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        @break
                                    @case('3')
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        @break
                                    @case('2')
                                        <li><i class="las la-star"></i></li>
                                        <li><i class="las la-star"></i></li>
                                        @break
                                    @case('1')
                                        <li><i class="las la-star"></i></li>
                                        @break
                                    @default
                                @endswitch
                            </ul>
                            <h6 class="title">{{ @$testimonial->language->title}}</h6>
                            <p class="desc">{!! @$testimonial->language->description!!}</p>
                            <div class="meta-section">
                                <div class="image">
                                    <img src="{{ getFileLink('80x80',  $testimonial->image) }}" alt="">
                                </div>
                                <div class="content">
                                    <h6 class="title">{{ @$testimonial->language->name}}</h6>
                                    <p class="desc">{{ @$testimonial->language->designation}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
<!-- End Testimonial Area -->