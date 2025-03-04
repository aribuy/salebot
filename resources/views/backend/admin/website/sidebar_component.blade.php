<div class="col-xxl-3 col-lg-4 col-md-4">
	<h3 class="section-title"{{ __('theme_option') }}></h3>
	<div class="bg-white redious-border py-3 py-sm-30 mb-30">
		<div class="email-tamplate-sidenav">
			<ul class="default-sidenav">
				@can('website.themes')
					<li>
						<a href="{{ route('admin.theme.options') }}"
						   class="{{ request()->routeIs('admin.theme.options') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-themeisle"></i></span>
							<span>{{ __('theme_options') }}</span>
						</a>
					</li>
				@endcan
				<li>
					<a href="{{ route('admin.menu') }}" class="{{ request()->routeIs('admin.menu') || request()->routeIs('admin.menu') || request()->routeIs('website.menu') ? 'active' : '' }}">
						<span class="icon"><i class="las la-heading"></i></span>
						<span>{{ __('menu') }}</span>
					</a>
				</li>
				@can('section.title')
					<li>
						<a href="{{ route('admin.section_title_subtitle') }}"
						   class="{{ request()->routeIs('admin.section_title_subtitle') ? 'active' : '' }}">
							<span class="icon"><i class="las la-chevron-circle-down"></i></span>
							<span>{{ __('section_title_subtitle') }}</span>
						</a>
					</li>
				@endcan
				@can('hero.section')
					<li>
						<a href="{{ route('admin.hero.section') }}"
						   class="{{ request()->routeIs('admin.hero.section') ? 'active' : '' }}">
							<span class="icon"><i class="las la-hand-point-up"></i></span>
							<span>{{ __('hero_section') }}</span>
						</a>
					</li>
				@endcan
				@if (active_theme() !=='darkbot')    
				@can('partner_logo.index')
					<li>
						<a href="{{ route('partner-logo.index') }}"
						   class="{{ request()->routeIs(['partner-logo.index', 'partner-logo.create', 'partner-logo.edit']) ? 'active' : '' }}">
							<span class="icon"><i class="las la-user-friends"></i></span>
							<span>{{ __('partner_logo') }}</span>
						</a>
					</li>
				@endcan
				@endif
				@if (active_theme() =='darkbot')
				@can('partner_logo.index')
					<li>
						<a href="{{ route('admin.growth.section') }}"
						   class="{{ request()->routeIs(['admin.growth.section']) ? 'active' : '' }}">
							<span class="icon"><i class="las la-user-friends"></i></span>
							<span>{{ __('growth') }}</span>
						</a>
					</li>
				@endcan
				@endif
				@if (active_theme() !=='martex' && active_theme() !=='darkbot')
				@can('story_section.index')
					<li>
						<a href="{{ route('story.index') }}"
						   class="{{ request()->routeIs(['story.index', 'story.create', 'story.edit']) ? 'active' : '' }}">
							<span class="icon"><i class="las la-history"></i></span>
							<span>{{ __('story') }}</span>
						</a>
					</li>
				@endcan
				@endif
				{{-- @can('counter.index') --}}
				@if (active_theme()=='martex')
				<li>
					<a href="{{ route('admin.theme.counter') }}"
					   class="{{ request()->routeIs(['admin.theme.counter']) ? 'active' : '' }}">
						<span class="icon"><i class="las la-percentage"></i></span>
						<span>{{ __('counter') }}</span>
					</a>
				</li>
				@endif
					
				{{-- @endcan --}}
				{{-- @can('counter.index') --}} 
				@if (active_theme()=='martex')
					<li>
						<a href="{{ route('website.service') }}"
						   class="{{ request()->routeIs(['website.service']) ? 'active' : '' }}">
							<span class="icon"><i class="las la-history"></i></span>
							<span>{{ __('services') }}</span>
						</a>
					</li>
					@endif
				{{-- @endcan --}}
				@if (active_theme() !=='martex')
				@can('unique_feature.index')
					<li>
						<a href="{{ route('unique-feature.index') }}"
						   class="{{ request()->routeIs(['unique-feature.index', 'unique-feature.create', 'unique-feature.edit']) ? 'active' : '' }}">
							<span class="icon"><i class="lar la-caret-square-down"></i></span>
							<span>{{ __('unique_feature') }}</span>
						</a>
					</li>
				@endcan
				@endif
				@can('feature.index')
					<li>
						<a href="{{ route('feature.index') }}"
						   class="{{ request()->routeIs(['feature.index', 'feature.edit', 'feature.create', ]) ? 'active' : '' }}">
							<span class="icon"><i class="lar la-caret-square-down"></i></span>
							<span>{{ __('feature') }}</span>
						</a>
					</li>
				@endcan
				@if (active_theme() !=='martex' && active_theme() !=='darkbot')
				@can('ai.chat')
					<li>
						<a href="{{ route('admin.ai.chat') }}"
						   class="{{ request()->routeIs('admin.ai.chat') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-accessible-icon"></i></span>
							<span>{{ __('ai_chat') }}</span>
						</a>
					</li>
				@endcan
				@endif
				@can('testimonials.index')
					<li>
						<a href="{{ route('testimonials.index') }}"
						   class="{{ request()->routeIs(['testimonials.index', 'testimonials.create', 'testimonials.edit']) ? 'active' : '' }}">
							<span class="icon"><i class="las la-address-book"></i></span>
							<span>{{ __('testimonial') }}</span>
						</a>
					</li>
				@endcan
				@can('advantage.index')
					<li>
						<a href="{{ route('advantage.index') }}"
						   class="{{ request()->routeIs('advantage.index', 'advantage.create', 'advantage.edit') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-jira"></i></span>
							<span>{{ __('advantage') }}</span>
						</a>
					</li>
				@endcan
				@can('faqs.index')
					<li>
						<a href="{{ route('faqs.index') }}"
						   class="{{ request()->routeIs(['faqs.index', 'faqs.create', 'faqs.edit']) ? 'active' : '' }}">
							<span class="icon"><i class="lar la-sticky-note"></i></span>
							<span>{{ __('faq') }}</span>
						</a>
					</li>
				@endcan
				@can('website.cta')
					<li>
						<a href="{{ route('admin.cta') }}"
						   class="{{ request()->routeIs('admin.cta') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-gripfire"></i></span>
							<span>{{ __('cta') }}</span>
						</a>
					</li>
				@endcan
				@can('footer.content')

					<li>
						<a href="{{ route('footer.content') }}"
						   class="@if(request()->routeIs('footer.primary-content') || request()->routeIs('footer.social-link') || request()->routeIs('footer.primary-content') || request()->routeIs('footer.newsletter-settings') || request()->routeIs('footer.useful-links') || request()->routeIs('footer.resource-links') || request()->routeIs('footer.quick-links') || request()->routeIs('footer.apps-links') || request()->routeIs('footer.payment-banner-settings') || request()->routeIs('footer.copyright')) active @endif">
							<span class="icon"><i class="las la-memory"></i></span>
							<span>{{ __('footer_content') }}</span>
						</a>
					</li>
				@endcan
				@can('website_setting.seo')
					<li>
						<a href="{{ route('website.seo') }}"
						   class="{{ request()->routeIs('website.seo') ? 'active' : '' }}">
							<span class="icon"><i class="las la-bullhorn"></i></span>
							<span>{{ __('website_seo') }}</span>
						</a>
					</li>
				@endcan
				@can('website_setting.custom_js')
					<li>
						<a href="{{ route('custom.js') }}"
						   class="{{ request()->routeIs('custom.js') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-js-square"></i></span>
							<span>{{ __('custom_js') }}</span>
						</a>
					</li>
				@endcan
				@can('website_setting.custom_css')
					<li>
						<a href="{{ route('custom.css') }}"
						   class="{{ request()->routeIs('custom.css') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-css3-alt"></i></span>
							<span>{{ __('custom_css') }}</span>
						</a>
					</li>
				@endcan
				@can('website_setting.google_setup')
					<li>
						<a href="{{ route('google.setup') }}"
						   class="{{ request()->routeIs('google.setup') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-google"></i></span>
							<span>{{ __('google_setup') }}</span>
						</a>
					</li>
				@endcan
				@can('website_setting.fb_pixel')
					<li>
						<a href="{{ route('fb.pixel') }}" class="{{ request()->routeIs('fb.pixel') ? 'active' : '' }}">
							<span class="icon"><i class="lab la-facebook-square"></i></span>
							<span>{{ __('fb_pixel') }}</span>
						</a>
					</li>
				@endcan
			</ul>
		</div>
	</div>
</div>
