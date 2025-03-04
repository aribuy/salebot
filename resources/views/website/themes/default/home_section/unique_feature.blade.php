
<div class="dreamd-split-accordion-area bg-white dreamd-section-gap-big pt--0" id="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                    <h3 class="title mb--0">{!! setting('unique_feature_section_title',app()->getLocale()) !!}</h3>
                </div>
            </div>
        </div>

        <div class="row row--40 mt_dec--30 split-accordion-wrapper">

            <div class="col-lg-5 col-md-12 col-sm-12 col-12 mt--30 sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                <div class="spa-accordion-style  accordion">
                    <div class="accordion" id="accordionExamplea">
                        @foreach($unique_features as $key => $unique_feature)
                            <div class="accordion-item card">
                                <h2 class="accordion-header card-header" id="heading{{ $key }}" style="font-family: Inter, Bangla883, sans-serif;">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                                        <img src="{{ getFileLink('80x80',  $unique_feature->icon) }}" alt="{{ @$unique_feature->language->title, app()->getLocale() }}" style="height: 24px; width:24px;">
                                        {{ @$unique_feature->language->title, app()->getLocale() }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body card-body">
                                        {!! @$unique_feature->language->description, app()->getLocale() !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 col-sm-12 col-12 mt--30 sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="200">
                <div class="split-image">
                    <img src="{{ getFileLink('714x300',setting('unique_feature_image')) }}" alt="Split Image">
                </div>
            </div>

        </div>
    </div>
</div>