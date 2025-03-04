<!-- Start Faq Area -->
<div class="dreamd-faq-area dreamd-section-gap pb--0" id="faq">
    <div class="container">
        <div class="row row--15 mt_dec--30 align-items-center">
            <div class="col-lg-5 col-12 mt--30">
                <div class="section-title mb--0 text-left sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                    <h3 class="title mb--20">{!! setting('faq_section_title',app()->getLocale()) !!}</h3>
                    <p class="description text-start mw-100 b1 mb--40">{!! setting('faq_section_subtitle',app()->getLocale()) !!}</p>
                </div>
            </div>

            <div class="col-lg-7 col-12 mt--30">
                <div class="spa-accordion-style style-two accordion">
                    <div class="accordion" id="accordion_two">
                        @foreach($faqs as $key=>$faq)
                            <div class="accordion-item card">
                                <h2 class="accordion-header card-header" id="headingQOne{{ $key }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseQOne{{ $key }}" aria-expanded="true"
                                            aria-controls="collapseQOne">
                                            {{ $faq->lang_question }}
                                    </button>
                                </h2>
                                <div id="collapseQOne{{ $key }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingQOne{{ $key }}" data-bs-parent="#accordion_two">
                                    <div class="accordion-body card-body">{!! $faq->lang_answer!!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Faq Area -->