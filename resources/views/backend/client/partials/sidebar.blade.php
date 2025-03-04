<header class="navbar-dark-v1">
        <div class="header-position">
        <span class="sidebar-toggler">
            <i class="las la-times"></i>
        </span>
		<div class="dashboard-logo d-flex justify-content-center align-items-center py-20">
			<a class="logo" href="">
				<img src="{{ setting('admin_logo') && @is_file_exists(setting('admin_logo')['original_image']) ? get_media(setting('admin_logo')['original_image']) : get_media('images/default/logo/logo.png') }}"
				     alt="Logo">
			</a>
			<a class="logo-icon" href="">
				<img src="{{ setting('admin_mini_logo') && @is_file_exists(setting('admin_mini_logo')['original_image']) ? get_media(setting('admin_mini_logo')['original_image']) : get_media('images/default/logo/logo-mini.png') }}"
				     alt="Logo">
			</a>
		</div>
		<nav class="side-nav">
			<ul id="accordionSidebar">
				<li class="{{ menuActivation(['client/dashboard'], 'active') }}">
					<a href="{{ route('client.dashboard') }}" role="button" aria-expanded="false"
					   aria-controls="dashboard">
						<i class="las la-tachometer-alt"></i>
						<span>{{ __('dashboard') }}</span>
					</a>
				</li>
				@can('manage_whatsapp')
					<li
							class="{{ menuActivation(['client/contacts-list', 'client/contact/import', 'client/bot-reply*', 'client/whatsapp/overview', 'client/templates', 'client/template/create', 'client/template/edit/*', 'client/segments', 'client/segment/edit/*', 'client/contacts', 'client/contact/create', 'client/contact/edit/*','client/chat-widget/list','client/chat-widget/create','client/chat-widget/edit/*','client/chat-widget/view/*','client/flow-builders','client/flow-builders/*'], 'active') }}">
						<a href="#manage" class="dropdown-icon" data-bs-toggle="collapse" role="button"
						   aria-expanded="{{ menuActivation(['client/contacts-list', 'client/contact/import', 'client/whatsapp/overview', 'client/templates', 'client/template/create', 'client/template/edit/*', 'client/segments', 'client/segments-edit/*', 'client/contacts', 'client/contact/create', 'client/contact/edit/*','client/chat-widget/list','client/chat-widget/create','client/chat-widget/edit/*','client/chat-widget/view/*','client/flow-builders','client/flow-builders/*'], 'true', 'false') }}"
						   aria-controls="manageClient">
							<i class="lab la-whatsapp"></i>
							<span>{{ __('whatsapp') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['client/contacts-list', 'client/contact/import', 'client/bot-reply*', 'client/whatsapp/overview', 'client/templates', 'client/template/create', 'client/template/edit/*', 'client/segments', 'client/segment/edit/*', 'client/contacts', 'client/contact/create', 'client/contact/edit/*','client/chat-widget/list','client/chat-widget/create','client/chat-widget/edit/*','client/chat-widget/view/*','client/flow-builders','client/flow-builders/*'], 'show') }}"
						    id="manage" data-bs-parent="#accordionSidebar">
							<li>
								<a class="{{ menuActivation(['client/whatsapp/overview'], 'active') }}"
								   href="{{ route('client.whatsapp.overview') }}">{{ __('overview') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/contacts-list','client/contacts-list/edit/*'], 'active') }}"
								   href="{{ route('client.contacts_list.index') }}">{{ __('contact_lists') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/contacts', 'client/contact/import', 'client/contact/create', 'client/contact/edit/*'], 'active') }}"
								   href="{{ route('client.contacts.index') }}">{{ __('contacts') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/segments', 'client/segment/edit/*'], 'active') }}"
								   href="{{ route('client.segments.index') }}">{{ __('segments') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/bot-reply*'], 'active') }}"
								   href="{{ route('client.bot-reply.index') }}">
									{{ __('bot_replies') }}
								</a>
							</li>
							@can('manage_flow')
							<li>
								<a class="{{ menuActivation(['client/flow-builders', 'client/flow-builders/*'], 'active') }}" href="{{ route('client.flow-builders.index') }}">
									<span>{{ __('flow_builder') }}</span>
								</a>
							</li>
							@endcan
							@can('manage_template')
							<li>
								<a class="{{ menuActivation(['client/templates','client/template/create','client/template/edit/*'], 'active') }}"
								   href="{{ route('client.templates.index') }}">{{ __('templates') }}</a>
							</li>
							@endif
							
							@php
								$chatWidgetActivated = addon_is_activated('chat_widget');
								$chatWidgetRouteExists = Route::has('client.chatwidget.index');
							@endphp
							@if($chatWidgetActivated && $chatWidgetRouteExists && auth()->user()->can('manage_widget'))
								<li>
									<a class="{{ menuActivation(['client/chat-widget/list','client/chat-widget/create','client/chat-widget/edit/*','client/chat-widget/view/*'], 'active') }}"
										href="{{ route('client.chatwidget.index') }}">{{ __('chatwidget') }}
										<div class="badges">Addon</div>
									</a>
								</li>
							@endif
						</ul>
					</li>
				@endcan
				@if(!empty(@auth()->user()->activeSubscription->telegram_access == '1'))
					@can('manage_telegram')
						<li
								class="{{ menuActivation(['client/telegram/overview', 'client/telegram/groups', 'client/telegram/subscribers/list', 'client/telegram/templates'], 'active') }}">
							<a href="#telegram" class="dropdown-icon" data-bs-toggle="collapse" role="button"
							   aria-expanded="{{ menuActivation([], 'true', 'false') }}" aria-controls="telegram">
								<i class="lab la-telegram"></i>
								<span>{{ __('telegram') }}</span>
							</a>
							<ul class="sub-menu collapse {{ menuActivation(['client/telegram/overview', 'client/telegram/groups', 'client/telegram/subscribers/list', 'client/telegram/templates'], 'show') }}"
							    id="telegram" data-bs-parent="#accordionSidebar">
								<li>
									<a class="{{ menuActivation(['client/telegram/overview'], 'active') }}"
									   href="{{ route('client.telegram.overview') }}">{{ __('overview') }}</a>
								</li>
								<li>
									<a class="{{ menuActivation(['client/telegram/groups'], 'active') }}"
									   href="{{ route('client.groups.index') }}">{{ __('groups') }}</a>
								</li>
								<li>
									<a class="{{ menuActivation(['client/telegram/subscribers/list'], 'active') }}"
									   href="{{ route('client.telegram.subscribers.index') }}">{{ __('subscribers') }}</a>
								</li>
							</ul>
						</li>
					@endcan
				@endif
				@can('manage_campaigns')
					<li class="{{ menuActivation(['client/whatsapp/campaigns','client/whatsapp/campaigns/*/view', 'client/telegram/campaigns','client/whatsapp/campaign/*','client/telegram/campaign/*'], 'active') }}">
						<a href="#campaigns" class="dropdown-icon" data-bs-toggle="collapse" role="button"
						   aria-expanded="{{ menuActivation([], 'true', 'false') }}" aria-controls="campaigns">
							<i class="lab la-telegram"></i>
							<span>{{ __('campaigns') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['client/whatsapp/campaigns','client/whatsapp/campaigns/*/view', 'client/telegram/campaigns','client/whatsapp/campaign/*','client/telegram/campaign/*'], 'show') }}"
						    id="campaigns" data-bs-parent="#accordionSidebar">
							<li>
								<a class="{{ menuActivation(['client/whatsapp/campaigns','client/whatsapp/campaigns/*/view','client/whatsapp/campaign/*'], 'active') }}"
								   href="{{ route('client.whatsapp.campaigns.index', ['campaign_type' => 'whatsapp']) }}">{{ __('whatsapp') }}</a>
							</li>
							@if(!empty(@auth()->user()->activeSubscription->telegram_access == '1'))
								<li>
									<a class="{{ menuActivation(['client/telegram/campaigns','client/telegram/campaign/*'], 'active') }}"
									   href="{{ route('client.telegram.campaigns.index', ['campaign_type' => 'telegram']) }}">{{ __('telegram') }}</a>
								</li>
							@endif
						</ul>
					</li>
				@endcan
				@can('manage_chat')
					<li class="{{ menuActivation('client/chat', 'active') }}">
						<a href="{{ route('client.chat.index') }}">
							<i class="las la-sms"></i>
							<span>{{ __('inbox') }}</span>
						</a>
					</li>
				@endcan
			
				@can('manage_team')
					<li
							class="{{ menuActivation(['client/team-list', 'client/team/create', 'client/team/edit/*'], 'active') }}">
						<a href="{{ route('client.team.index') }}">
							<i class="las la-user-tie"></i>
							<span>{{ __('team_member') }}</span>
						</a>
					</li>
				@endcan
				@can('manage_ticket')
					<li class="{{ menuActivation(['client/tickets', 'client/tickets/*'], 'active') }}">
						<a href="{{ route('client.tickets.index') }}">
							<i class="las la-ticket-alt"></i>
							<span>{{ __('my_ticket') }}</span>
						</a>

					</li>
				@endcan
				@can('manage_ai_writer')
					<li class="{{ menuActivation(['client/ai-writer', 'client/ai-writer-setting'], 'active') }}">
						<a href="#ai" class="dropdown-icon" data-bs-toggle="collapse" role="button"
						   aria-expanded="{{ menuActivation(['client/ai-writer', 'client/ai-writer-setting'], 'true', 'false') }}"
						   aria-controls="ai">
							<i class="lab la-rocketchat"></i>
							<span>{{ __('ai_assistent') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['client/ai-writer', 'client/ai-writer-setting'], 'show') }}"
							id="ai" data-bs-parent="#accordionSidebar">
							<li>
								<a class="{{ menuActivation('client/ai-writer', 'active') }}"
									href="{{ route('client.ai.writer') }}">
									<span>{{ __('ai_writer') }}</span>
								</a>
							</li>
							<li><a class="{{ menuActivation('client/ai-writer-setting', 'active') }}"
									href="{{ route('client.ai_writer.setting') }}">{{ __('setting') }}</a></li>
						</ul>
					</li>
				@endcan 

				@can('manage_setting')
					<li class="{{ menuActivation(['client/whatsapp-settings','client/billing/details','client/telegram-settings','client/general-settings'], 'active') }}">
						<a href="#setting" class="dropdown-icon" data-bs-toggle="collapse" role="button"
						   aria-expanded="{{ menuActivation(['client/whatsapp-settings','client/billing/details','client/telegram-settings','client/general-settings'], 'true', 'false') }}" aria-controls="setting">
							<i class="las la-cog"></i>
							<span>{{ __('setting') }}</span>
						</a>
						<ul class="sub-menu collapse {{ menuActivation(['client/whatsapp-settings','client/billing/details','client/telegram-settings','client/general-settings'], 'show') }}"
						    id="setting" data-bs-parent="#accordionSidebar">
							<li>
								<a class="{{ menuActivation(['client/whatsapp-settings'], 'active') }}"
								   href="{{route('client.whatsapp.settings')}}">{{ __('whatsapp') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/telegram-settings'], 'active') }}"
								   href="{{route('client.telegram.settings')}}">{{ __('telegram') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/general-settings'], 'active') }}"
								   href="{{route('client.general.settings')}}">{{ __('general_setting') }}</a>
							</li>
							<li>
								<a class="{{ menuActivation(['client/billing/details'], 'active') }}"
								   href="{{ route('client.billing.details') }}">{{ __('billing_details') }}</a>
							</li>
						</ul>
					</li>
				@endcan
				<li class="{{ menuActivation(['client/api'], 'active') }}">
					<a href="{{ route('client.settings.api') }}">
						<i class="las la-paperclip"></i>
						<span>{{ __('api') }}</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</header>
