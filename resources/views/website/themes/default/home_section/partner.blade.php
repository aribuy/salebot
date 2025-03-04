<!-- Start Brand Area  -->
<div class="cns-brand-area bg-white ptb--50 border--bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb--0 text-center">
                    <h5 class="title mb--32 mt-0">{!! setting('client_section_title',app()->getLocale()) !!}</h5>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="brand-slick-activition" data-slick='{"slidesToShow": 6, "slidesToScroll": 6}'>
                    @foreach($partner_logos as $key=>$partner_logo)

                        <div class="brand-image" {{ $key }}> 
                            <a href="#">
                                <img src="{{ getFileLink('80x80', $partner_logo->image) }}" alt="{{ $partner_logo->name }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Brand Area  -->