
<div class="dreamd-service-area bg-white dreamd-section-gap-big" id="stories">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="100">
                    <h3 class="title mb--0">{!! setting('story_section_title',app()->getLocale()) !!}</h3>
                    <p class="description b1">{!! setting('story_section_subtitle',app()->getLocale()) !!}</p>
                </div>
            </div>
        </div>
        <div class="row row--15 mt_dec--30 service-wrapper">
            @foreach($stories as $key=>$story)
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mt--30 sal-animate" data-sal="slide-up" data-sal-duration="700" data-sal-delay="300">
                <div class="card-box card-style-1 text-left">
                    <div class="inner">
                        <div class="image">
                            <a href="#">
                                <img src="{{ getFileLink('original_image',  $story->image) }}"  alt="card Images">
                            </a>
                        </div>
                        <div class="content">
                            <p class="description">{!! @$story->language->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
