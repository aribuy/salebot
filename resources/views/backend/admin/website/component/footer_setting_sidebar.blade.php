<ul class="nav pb-12 mb-20" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.primary-content') }}"
            class="nav-link ps-0 {{ request()->routeIs('footer.primary-content') ? 'active' : '' }}">
            <span>{{ __('primary_content') }}</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.useful-links') }}"
            class="nav-link ps-0 {{ request()->routeIs('footer.useful-links') ? 'active' : '' }}">
            <span>{{ __('useful_links') }}</span>
        </a>
    </li>

    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.quick-links') }}"
            class="nav-link ps-0 {{ request()->routeIs('footer.quick-links') ? 'active' : '' }}">
            <span>{{ __('quick_links') }}</span>
        </a>
    </li>

    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.payment-banner-settings') }}"
            class="nav-link ps-0 {{ request()->routeIs('footer.payment-banner-settings') ? 'active' : '' }}">
            <span>{{ __('payment_method_banners') }}</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.copyright') }}"
            class="nav-link ps-0 {{ request()->routeIs('footer.copyright') ? 'active' : '' }}">
            <span>{{ __('copyright') }}</span>
        </a>
    </li>
    @if (active_theme() == 'martex')
        <li class="nav-item" role="presentation">
            <a href="{{ route('footer.social-link') }}"
                class="nav-link ps-0 {{ request()->routeIs('footer.social-link') ? 'active' : '' }}">
                <span>{{ __('social_link') }}</span>
            </a>
        </li>
    @endif

    @if (active_theme() == 'martex')
        @if (hasPermission('footer.newsletter-settings'))
            <li class="nav-item" role="presentation">
                <a href="{{ route('footer.newsletter-settings') }}"
                    class="nav-link ps-0 {{ request()->routeIs('footer.newsletter-settings') ? 'active' : '' }}">
                    <span>{{ __('newsletter_settings') }}</span>
                </a>
            </li>
        @endif
    @endif


</ul>
