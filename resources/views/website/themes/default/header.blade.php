<!-- Start Header Area  -->
<header class="dreamd-header header-default header-transparent header-not-transparent header-sticky">
	
	<div class="container container-lg-fluid container-xl position-relative">
		<div class="row align-items-center justify-content-between row--0 mx-sm-2 mx-lg-0">

			<div class="col-xxl-3 col-lg-2 col-md-12 col-12">
				<div class="d-flex align-items-center justify-content-between">

					<div class="logo">
						<a href="{{url('/')}}">
							<img class="logo"
							     src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80',[]) }}"
							     alt="Corporate Logo">
						</a>
					</div>
					<!-- Start Mobile-Menu-Bar -->
					<div class="mobile-menu-bar ml--5 d-block d-lg-none">
						<div class="hamberger">
							<a class="hamberger-button" data-bs-toggle="offcanvas" href="#offcanvasExample"
							   role="button" aria-controls="offcanvasExample">
								<i class="las la-bars"></i>
							</a>
						</div>
					</div>
					<!-- Start Mobile-Menu-Bar -->
				</div> 
			</div>

			<div class="col-xxl-9 col-lg-10 col-md-6 col-4 position-static d-none d-lg-block">
				<div class="header-right justify-content-lg-between justify-content-end">
					<nav class="mainmenu-nav d-none d-lg-block">
						<ul class="mainmenu">
							@if(setting('show_default_menu_link') == 1)
								@if($menu_language && is_array(setting('header_menu')) ? count(setting('header_menu')) : 0 != 0 && setting('header_menu') != [])
									@foreach($menu_language as $key => $value)
										<li><a href="{{ @$value['url']  }}">{{ @$value['label'] }}</a></li>
									@endforeach
								@endif
							@endif
							<?php
							$language_switcher = setting('language_switcher');
							if($language_switcher==''){
							  $language_switcher = 1;
							}
						  ?>
						  @if ($language_switcher)
							<li class="has-dropdown has-menu-child-item position-relative">
								@php
									$active_locale = '';
									$languages = app('languages');
									$locale_language = $languages->where('locale', app()->getLocale())->first();
									if ($locale_language) {
										$active_locale = $locale_language->name;
									}
								@endphp
								<a href="">
									{{ $active_locale }}
									<i class="las la-angle-down"></i>
								</a>
								<ul class="submenu">
									@foreach($languages as $language)
										<li class="language-selector">
											<a href="{{ setLanguageRedirect($language->locale) }}">
												{{ $language->name }}
											</a>
										</li>
									@endforeach
								</ul>
							</li>
							@endif
						</ul>
					</nav>
					<!-- Start Header Btn  -->
					<div class="header-btn">
						@if(Auth::check())
							@if(Auth::user()->role_id == 1)
								<a class="btn-default round has-right-icon"
								   href="{{route('admin.dashboard')}}">{{__('dashboard')}}<i
											class="las la-angle-right"></i></a>
							@else
								<a class="btn-default round has-right-icon"
								   href="{{route('client.dashboard')}}">{{__('dashboard')}}<i
											class="las la-angle-right"></i></a>
							@endif
						@else
							<a class="btn-link-dflt has-left-icon mr--10" href="{{route('login')}}"><i
										class="las la-user"></i>
								{{__('login')}}</a>
							<a class="btn-default round has-right-icon"
							   href="{{route('register')}}">{{__('get_started')}} <i class="las la-angle-right"></i></a>
						@endif
					</div>
					<!-- End Header Btn  -->
				</div>
			</div>
		</div>
	</div>
</header>

<!-- Start Mobile-Menu -->
<div class="offcanvas offcanvas-start mobile-menu d-lg-none" tabindex="-1" id="offcanvasExample"
     aria-label="offcanvasExampleLabel">
	<div class="mobile-menu__header">
		<div class="logo">
			<a href="{{url('/')}}">
				<img class="logo"
				     src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80',[]) }}"
				     alt="Corporate Logo">
			</a>
		</div>

		<button type="button" class="hamberger-button-close" data-bs-dismiss="offcanvas" aria-label="Close">
			<i class="las la-times"></i></button>
	</div>

	<div class="header-right justify-content-lg-between justify-content-start">

		<nav class="mainmenu-nav">
			<ul class="mobile-menu__mainmenu mainmenu d-block">
				@if(setting('show_default_menu_link') == 1)
					@if($menu_language && is_array(setting('header_menu')) ? count(setting('header_menu')) : 0 != 0 && setting('header_menu') != [])
						@foreach($menu_language as $key => $value)
							<li><a href="{{ @$value['url']  }}">{{ @$value['label'] }}</a></li>
						@endforeach
					@endif
				@endif
				@if(Auth::check())
					@if(Auth::user()->role_id == 1)
						<li><a href="{{route('admin.dashboard')}}">{{__('dashboard')}}</a></li>
					@else
						<li><a href="{{route('client.dashboard')}}">{{__('dashboard')}}</a></li>
					@endif
				@else
					<li><a href="{{route('login')}}">{{__('login')}}</a></li>
					<li><a href="{{route('register')}}">{{__('signup')}}</a></li>
				@endif
					<li class="dropdown">
						@php
							$active_locale = '';
							$languages = app('languages');
							$locale_language = $languages->where('locale', app()->getLocale())->first();
							if ($locale_language) {
								$active_locale = $locale_language->name;
							}
						@endphp
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $active_locale }}</a>
						<ul class="dropdown-menu">
							@foreach($languages as $language)
								<li class="language-selector">
									<a href="{{ setLanguageRedirect($language->locale) }}">
										{{ $language->name }}
									</a>
								</li>
							@endforeach
						</ul>
					</li>
			</ul>
		</nav>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });
</script>


