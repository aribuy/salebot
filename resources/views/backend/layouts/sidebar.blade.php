
<header class="navbar-dark-v1">
	<div class="header-position">
        <span class="sidebar-toggler">
            <i class="las la-times"></i>
        </span>
		<div class="dashboard-logo d-flex justify-content-center align-items-center py-20">
			<a class="logo" href="{{ route('admin.dashboard') }}">
				<img src="{{ setting('admin_logo') && @is_file_exists(setting('admin_logo')['original_image']) ? get_media(setting('admin_logo')['original_image']) : get_media('images/default/logo/logo.png') }}"
				     alt="Logo">
			</a>
			@can('dashboard_statistic')
				<a class="logo-icon" href="{{ route('admin.dashboard') }}">
					<img src="{{ setting('admin_mini_logo') && @is_file_exists(setting('admin_mini_logo')['original_image']) ? get_media(setting('admin_mini_logo')['original_image']) : get_media('images/default/logo/logo-mini.png') }}"
					     alt="Logo">
				</a>
			@endcan
		</div>

		<nav class="side-nav">
			<ul id="accordionSidebar">
				@can('admin.dashboard')
					<li class="{{ menuActivation(['admin/dashboard'], 'active') }}">
						<a href="{{ route('admin.dashboard') }}" role="button" aria-expanded="false"
						   aria-controls="dashboard">
							<i class="las la-tachometer-alt"></i>
							<span>{{ __('dashboard') }}</span>
						</a>
					</li>
				@endcan
				@can('client.index')
					<li class="{{ menuActivation(['admin/clients', 'admin/clients*'], 'active') }}">
						<a href="{{ route('clients.index') }}">
							<i class="las la-user"></i>
							<span>{{ __('manage_client') }}</span>
						</a>
					</li>
				@endcan
				@can('subscription.index')
					<li class="{{ menuActivation('admin/subscriptions', 'active') }}">
						<a href="{{ route('packages.subscribe-list') }}">
							<i class="las la-money-bill"></i>
							<span>{{ __('subscription') }}</span>
						</a>
					</li>
				@endcan
				@can('price_plans.index')
					<li class="{{ menuActivation('admin/plans*', 'active') }}">
						<a href="{{ route('plans.index') }}">
							<i class="las la-money-bill-wave"></i>
							<span>{{ __('price_plans') }}</span>
						</a>
					</li>
				@endcan
				@can('staffs.index' || 'roles.index' )
					<li
							class="{{ menuActivation(['admin/staffs', 'admin/staffs/create', 'admin/staffs/*/edit', 'admin/roles/*/edit', 'admin/roles/create', 'admin/roles'], 'active') }}">
						<a href="#staff" class="dropdown-icon" data-bs-toggle="collapse" role="button"
						   aria-expanded="{{ menuActivation(['admin/staffs', 'admin/staffs/create', 'admin/staffs/*/edit', 'admin/roles/*/edit', 'admin/roles/create', 'admin/roles'], 'true', 'false') }}"
						   aria-controls="staff">
							<i class="las la-user-friends"></i>
							<span>{{ __('staff') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/staffs', 'admin/roles/create', 'admin/staffs/create', 'admin/staffs/*/edit', 'admin/roles/*/edit', 'admin/roles'], 'show') }}"
						    id="staff" data-bs-parent="#accordionSidebar">
							@can('staffs.index')
								<li><a class="{{ menuActivation('admin/staff*', 'active') }}"
								       href="{{ route('staffs.index') }}">{{ __('all_staff') }}</a></li>
							@endcan
							@can('roles.index')
								<li><a class="{{ menuActivation('admin/roles*', 'active') }}"
								       href="{{ route('roles.index') }}">{{ __('roles') }}</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan
				@can('ai.writer' || 'ai.setting')
					<li class="{{ menuActivation(['admin/ai-writer', 'admin/ai-writer-setting'], 'active') }}">
						<a href="#ai" class="dropdown-icon" data-bs-toggle="collapse" role="button"
						   aria-expanded="{{ menuActivation(['admin/ai-writer', 'admin/ai-writer-setting'], 'true', 'false') }}"
						   aria-controls="ai">
							<i class="lab la-rocketchat"></i>
							<span>{{ __('ai_assistent') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/ai-writer', 'admin/ai-writer-setting'], 'show') }}"
						    id="ai" data-bs-parent="#accordionSidebar">
							@can('ai.writer')
								<li>
									<a class="{{ menuActivation('admin/ai-writer', 'active') }}"
									   href="{{ route('ai.writer') }}">
										<span>{{ __('ai_writer') }}</span>
									</a>
								</li>
							@endcan
							@can('ai.setting')
								<li><a class="{{ menuActivation('admin/ai-writer-setting', 'active') }}"
								       href="{{ route('ai_writer.setting') }}">{{ __('setting') }}</a></li>
							@endcan
						</ul>
					</li>
				@endcan

				@can('payment_methods.index')
					<li class="{{ menuActivation('admin/payment-gateway', 'active') }}">
						<a href="{{ route('payment.gateway') }}">
							<i class="las la-credit-card"></i>
							<span>{{ __('payment_gateway') }}</span>
						</a>
					</li>
				@endcan
				@can('custom-notification.index')
					<li class="{{ menuActivation(['admin/custom-notification', 'admin/custom-notification*'], 'active') }}">
						<a href="{{ route('custom-notification.index') }}">
							<i class="las la-bell"></i>
							<span>{{ __('notification') }}</span>
						</a>
					</li>
				@endcan
				@can('tickets.index' || 'departments.index')
					<li
							class="{{ menuActivation(['admin/departments', 'admin/departments/*', 'admin/tickets', 'admin/tickets/*','admin/ticket-reply-edit/*'], 'active') }}">
						<a href="#support" class="dropdown-icon" data-bs-toggle="collapse"
						   aria-expanded="{{ menuActivation(['admin/departments', 'admin/departments/*','admin/ticket-reply-edit/*', 'admin/tickets', 'admin/tickets/*'], 'true', 'false') }}"
						   aria-controls="support">
							<i class="las la-headset"></i>
							<span>{{ __('support') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/departments', 'admin/departments/*','admin/ticket-reply-edit/*', 'admin/tickets', 'admin/tickets/*', 'admin/student-faqs*'], 'show') }}"
						    id="support" data-bs-parent="#accordionSidebar">

							@can('tickets.index')
								<li>
									<a class="{{ menuActivation(['admin/tickets', 'admin/tickets/*','admin/ticket-reply-edit/*'], 'active') }}"
									   href="{{ route('tickets.index') }}">{{ __('ticket') }}</a></li>
							@endcan

							@can('departments.index')
								<li>
									<a class="{{ menuActivation(['admin/departments', 'admin/departments/*'], 'active') }}"
									   href="{{ route('departments.index') }}">{{ __('department') }}</a></li>
							@endcan
						</ul>
					</li>
				@endcan
				@can('website.themes' || 'section.title'|| 'hero.section' || 'ai.chat'|| 'website.cta'|| 'footer.content'|| 'website_setting.seo'|| 'website_setting.custom_js'
				|| 'website_setting.custom_css' || 'website_setting.google_setup' || 'website_setting.fb_pixel')
					<li class="{{ menuActivation(['admin/website/theme-options*', 'admin/website/menu*', 'admin/website/section-title-subtitle*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*', 'admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting', 'admin/website/pages*'], 'active') }}">
						<a href="#website-setting" class="dropdown-icon" data-bs-toggle="collapse"
						   aria-expanded="{{ menuActivation(['admin/website/theme-options*', 'admin/website/menu*', 'admin/website/section-title-subtitle*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*', 'admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting', 'admin/website/pages*'], 'active') }}"
						   aria-controls="website-setting">
							<i class="las la-cog"></i>
							<span>{{ __('website_settings') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/website/theme-options*', 'admin/website/menu*', 'admin/website/section-title-subtitle*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*','admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting', 'admin/website/pages*'], 'show') }}"
						    id="website-setting" data-bs-parent="#accordionSidebar">
							@can('website.themes' || 'section.title'|| 'hero.section' || 'ai.chat'|| 'website.cta'|| 'footer.content'|| 'website_setting.seo'|| 'website_setting.custom_js'
							|| 'website_setting.custom_css' || 'website_setting.google_setup' || 'website_setting.fb_pixel')
								<li>
									<a class="{{ menuActivation(['admin/website/theme-options*', 'admin/website/menu*', 'admin/website/section-title-subtitle*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting'], 'active') }}"
									   href="{{ route('admin.theme.options') }}">{{ __('all_setting') }}</a></li>
							@endcan
							@can('pages')
								<li><a href="{{ route('pages.index') }}"
								       class="{{ menuActivation('admin/website/pages*', 'active') }}">{{ __('pages') }}</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan

				@can('email.template' || 'email.server_configuration')
					<li
							class="{{ menuActivation(['admin/email/server-configuration*', 'admin/email/template*'], 'active') }}">
						<a href="#emailSetting" class="dropdown-icon" data-bs-toggle="collapse"
						   aria-expanded="{{ menuActivation(['admin/email/server-configuration*', 'admin/email/template*'], 'true', 'false') }}"
						   aria-controls="emailSetting">
							<i class="las la-envelope"></i>
							<span>{{ __('email_settings') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/email/server-configuration*', 'admin/email/template*'], 'show') }}"
						    id="emailSetting" data-bs-parent="#accordionSidebar">

							@can('email.template')
								<li><a class="{{ menuActivation('admin/email/template*', 'active') }}"
								       href="{{ route('email.template') }}">{{ __('email_template') }}</a></li>
							@endcan

							@can('email.server_configuration')
								<li><a class="{{ menuActivation('admin/email/server-configuration*', 'active') }}"
								       href="{{ route('email.server-configuration') }}">{{ __('server_configuration') }}</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan

				@can('pusher.notification' || 'onesignal.notification','general.setting' || 'preference' || 'currencies.index' || 'languages.index' || 'admin.cache' || 'admin.panel-setting' || 'admin.firebase' ||'storage.setting'|| 'chat.messenger'
							|| 'miscellaneous.setting' || 'cron.setting' || 'countries.index' )
					<li class="{{ menuActivation(['admin/pusher-notification', 'admin/one-signal-notification','admin/currencies', 'admin/countries', 'admin/states', 'admin/cities', 'admin/languages', 'admin/language/*', 'admin/system-setting', 'admin/cache', 'admin/firebase', 'admin/preference', 'admin/storage-setting', 'admin/chat-messenger', 'admin/panel-setting', 'admin/miscellaneous-setting','admin/cron-setting', 'admin/refund-setting'], 'active') }}">
						<a href="#settingTools" class="dropdown-icon" data-bs-toggle="collapse"
						   aria-expanded="{{ menuActivation(['admin/pusher-notification', 'admin/one-signal-notification','admin/currencies', 'admin/countries', 'admin/states', 'admin/cities', 'admin/languages', 'admin/language/*', 'admin/gsystem-setting', 'admin/cache', 'admin/firebase', 'admin/preference', 'admin/storage-setting', 'admin/chat-messenger', 'admin/panel-setting', 'admin/miscellaneous-setting','admin/cron-setting', 'admin/refund-setting'], 'true', 'false') }}"
						   aria-controls="settingTools">
							<i class="las la-cog"></i>
							<span>{{ __('system_setting') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/pusher-notification', 'admin/one-signal-notification','admin/currencies', 'admin/countries', 'admin/states', 'admin/cities', 'admin/languages', 'admin/language/*', 'admin/system-setting', 'admin/cache', 'admin/firebase', 'admin/preference', 'admin/storage-setting', 'admin/chat-messenger', 'admin/panel-setting', 'admin/miscellaneous-setting', 'admin/cron-setting','admin/refund-setting'], 'show') }}"
						    id="settingTools" data-bs-parent="#accordionSidebar">
							@can('general.setting')
								<li><a class="{{ menuActivation('admin/system-setting', 'active') }}"
								       href="{{ route('general.setting') }}">{{ __('general_setting') }}</a></li>
							@endcan
							@can('currencies.index')
								<li><a class="{{ menuActivation('admin/currencies', 'active') }}"
								       href="{{ route('currencies.index') }}">{{ __('currency') }}</a></li>
							@endcan
							@can('languages.index')
								<li><a class="{{ menuActivation(['admin/languages', 'admin/language/*'], 'active') }}"
								       href="{{ route('languages.index') }}">{{ __('language_settings') }}</a></li>
							@endcan
							@can('pusher.notification')
								<li><a class="{{ menuActivation('admin/pusher-notification', 'active') }}"
								       href="{{ route('pusher.notification') }}">{{ __('pusher') }}</a></li>
							@endcan

							@can('onesignal.notification')
								<li><a class="{{ menuActivation('admin/one-signal-notification', 'active') }}"
								       href="{{ route('onesignal.notification') }}">{{ __('onesignal') }}</a></li>
							@endcan
							@can('admin.cache')
								<li><a class="{{ menuActivation('admin/cache', 'active') }}"
								       href="{{ route('admin.cache') }}">{{ __('cache_setting') }}</a></li>
							@endcan
							@can('admin.panel-setting')
								<li><a class="{{ menuActivation('admin/panel-setting', 'active') }}"
								       href="{{ route('admin.panel-setting') }}">{{ __('admin_panel_setting') }}</a>
								</li>
							@endcan
							@can('admin.firebase')
								<li><a class="{{ menuActivation('admin/firebase', 'active') }}"
								       href="{{ route('admin.firebase') }}">{{ __('firebase') }}</a></li>
							@endcan
							@can('storage.setting')
								<li><a class="{{ menuActivation('admin/storage-setting', 'active') }}"
								       href="{{ route('storage.setting') }}">{{ __('storage_setting') }}</a></li>
							@endcan
							@can('chat.messenger')
								<li><a class="{{ menuActivation('admin/chat-messenger', 'active') }}"
								       href="{{ route('chat.messenger') }}">{{ __('chat_messenger') }}</a></li>
							@endcan

							@can('miscellaneous.setting')
								<li><a class="{{ menuActivation('admin/miscellaneous-setting', 'active') }}"
								       href="{{ route('miscellaneous.setting') }}">{{ __('miscellaneous') }}</a></li>
							@endcan
							@can('cron.setting')
								<li><a class="{{ menuActivation('admin/cron-setting', 'active') }}"
								       href="{{ route('cron.setting') }}">{{ __('cron_job') }}</a></li>
							@endcan
							@can('countries.index')
								<li><a class="{{ menuActivation('admin/countries', 'active') }}"
								       href="{{ route('countries.index') }}">{{ __('country') }}</a></li>
							@endcan
						</ul>
					</li>
				@endcan
				@can('addon.index')
					<li class="{{ menuActivation(['admin/addon'], 'active') }}">
						<a href="#addons" class="dropdown-icon" data-bs-toggle="collapse"
						   aria-expanded="{{ menuActivation(['admin/addon'], 'true', 'false') }}"
						   aria-controls="addons">
							<i class="las la-puzzle-piece"></i>
							<span>{{ __('addon') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/addon'], 'show') }}"
							id="addons" data-bs-parent="#accordionSidebar">
								{{-- <li><a class="{{ menuActivation([], 'active') }}"
									   href="{{ route('addon.index') }}">{{ __('available_addons') }}</a>
								</li> --}}
								<li>
									<a class="{{ menuActivation('admin/addon', 'active') }}"
									   href="{{ route('addon.index') }}">{{ __('installed_addons') }}</a>
								</li>
						</ul>
					</li>
				@endcan
				@can('system.update' || 'server.info')
					<li class="{{ menuActivation(['admin/server-info', 'admin/system-info', 'admin/extension-library', 'admin/file-system-permission', 'admin/system-update'], 'active') }}">
						<a href="#utility" class="dropdown-icon" data-bs-toggle="collapse"
						   aria-expanded="{{ menuActivation(['admin/server-info', 'admin/system-info', 'admin/extension-library', 'admin/file-system-permission', 'admin/system-update'], 'true', 'false') }}"
						   aria-controls="utility">
							<i class="las la-cogs"></i>
							<span>{{ __('utility') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['admin/server-info', 'admin/system-info', 'admin/extension-library', 'admin/file-system-permission', 'admin/system-update'], 'show') }}"
						    id="utility" data-bs-parent="#accordionSidebar">

							@can('system.update')
								<li><a class="{{ menuActivation(['admin/system-update'], 'active') }}"
								       href="{{ route('system.update') }}">{{ __('system_update') }}</a></li>
							@endcan
							@can('server.info')
								<li>
									<a class="{{ menuActivation(['admin/server-info', 'admin/system-info', 'admin/extension-library', 'admin/file-system-permission'], 'active') }}"
									   href="{{ route('server.info') }}">{{ __('server_information') }}</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan
			</ul>
		</nav>
	</div>
</header>
