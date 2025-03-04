<div>
    @switch($testimonial->rating)
        @case('5')
             <span class="stars"><i class="las la-star"></i></span>
             <span class="stars"><i class="las la-star"></i></span>
             <span class="stars"><i class="las la-star"></i></span>
             <span class="stars"><i class="las la-star"></i></span>
             <span class="stars"><i class="las la-star"></i></span>
            @break
        @case('4')
        <span class="stars"><i class="las la-star"></i></span>
        <span class="stars"><i class="las la-star"></i></span>
        <span class="stars"><i class="las la-star"></i></span>
        <span class="stars"><i class="las la-star"></i></span>
            @break
        @case('3')
        <span class="stars"><i class="las la-star"></i></span>
        <span class="stars"><i class="las la-star"></i></span>
        <span class="stars"><i class="las la-star"></i></span>
            @break
        @case('2')
        <span class="stars"><i class="las la-star"></i></span>
        <span class="stars"><i class="las la-star"></i></span>
            @break
        @case('1')
        <span class="stars"><i class="las la-star"></i></span>
            @break
        @default
    @endswitch
</div>
