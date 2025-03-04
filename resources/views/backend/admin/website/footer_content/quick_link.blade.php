@extends('backend.layouts.master')
@section('title', __('quick_link_settings'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('quick_links') }}</h3>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border website-setting-social-link p-20 p-sm-30">
                        @include('backend.admin.website.component.footer_setting_sidebar')
                        <form action="{{ route('footer.update-menu') }}" method="POST" class="form">@csrf
                            <input type="hidden" name="menu_name" value="footer_quick_link_menu">
                            <input type="hidden" name="site_lang" value="{{$lang}}">
                            <div class="row gx-20">
                                <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                    <input type="hidden" name="show_quick_link" value="{{ setting('show_quick_link') == 1 ? 1 : 0 }}">
                                    <label class="form-label"
                                           for="show_quick_link">{{ __('show_quick_link') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" value="1" id="show_quick_link"
                                               class="sandbox_mode" {{ setting('show_quick_link') == 1 ? 'checked' : '' }}>
                                        <label for="show_quick_link"></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{__('menu_title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title" name="quick_link_title"
                                                value="{{ setting('quick_link_title',app()->getLocale()) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="title_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="cf sortable-menu-section ">
                                        <div class="dd" id="menuSortable">
                                            <ol class="dd-list" id="dd-list">
                                            @if($menu_language && is_array(setting('footer_quick_link_menu')) ? count(setting('footer_quick_link_menu')) : 0 != 0 && setting('footer_quick_link_menu') != [])
                                                @foreach($menu_language as $key => $value)
                                                    <li class="dd-item dd3-item" data-id="0">
                                                        <input type="hidden" name="menu_lenght[]" id="menu_lenght" value="1">
                                                        <input type="hidden" name="lang" id="lang" value="{{$lang}}">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content sortable-section mb-4">
                                                            <ul class="sortable-menu-icon">
                                                                <li class="menuMove">
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="$(this).closest('.dd-item').remove()" class="delete-icon"><i
                                                                            class="las la-trash-alt"></i></a>
                                                                </li>
                                                            </ul>
                                                            <div class="row gx-20 align-items-center">
                                                                <div class="col-lg-3">
                                                                    <input type="text" name="label[]" id="label" value="{{ @$value['label'] }}" class="form-control rounded-2"
                                                                    required placeholder="{{__('Label')}}">
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <div class="d-flex align-items-center gap-4">
                                                                        <input type="text" class="form-control rounded-2" name="url[]" value="{{ @$value['url'] == 'javascript:void(0)' ? '#' : @$value['url'] }}" required placeholder="{{__('Link')}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(collect($value)->count() > 2)
                                                        <ol class="dd-list">
                                                            @if(@is_array($value[0]))
                                                                @foreach(array_splice($value, 2) as $j => $sub)

                                                                    <li class="dd-item dd3-item" data-id="5">
                                                                        <input type="hidden" name="menu_lenght[]" id="menu_lenght" value="1">
                                                                        <input type="hidden" name="lang" id="lang" value="{{$lang}}">
                                                                        <div class="dd-handle dd3-handle"></div>
                                                                        <div class="dd3-content sortable-section mb-4">
                                                                            <ul class="sortable-menu-icon">
                                                                                <li class="menuMove">
                                                                                </li>
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="delete-icon"><i
                                                                                            class="las la-trash-alt"></i></a>
                                                                                </li>
                                                                            </ul>


                                                                        </div>
                                                                        <!-- End Sortable Section -->
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ol>
                                                        @endif
                                                    </li>
                                                @endforeach
                                                @else

                                                    <!-- Start Sortable Section -->
                                                    <li class="dd-item dd3-item" data-id="0">
                                                        <input type="hidden" name="menu_lenght[]" id="menu_lenght" value="1">
                                                        <input type="hidden" name="lang" id="lang" value="">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content sortable-section mb-4">
                                                            <ul class="sortable-menu-icon">
                                                                <li class="menuMove">
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="$(this).closest('.dd-item').remove()" class="delete-icon"><i
                                                                            class="las la-trash-alt"></i></a>
                                                                </li>
                                                            </ul>
                                                            <div class="row gx-20 align-items-center">
                                                                <div class="col-lg-3">
                                                                    <input type="text" name="label[]" class="form-control rounded-2"
                                                                        placeholder="label">
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <div class="d-flex align-items-center gap-4">
                                                                        <input type="text" name="url[]" class="form-control rounded-2"
                                                                        placeholder="{{ __('link') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <!-- End Sortable Section -->
                                                @endif

                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-30">
                                    <button type="button" class="btn sg-btn-primary" id="add-menu-item">Add More</button>
                                    <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.admin.website.component.new_menu')
@endsection
@push('js_asset')
    <script src="{{ static_asset('admin/js/jquery.nestable.min.js') }}"></script>
@endpush
@push('js')
    <script>
        $(document).ready(function () {
            $('#menuSortable').nestable({
            group: 'list',
            animation: 200,
            ghostClass: 'ghost',
            maxDepth: 1,
            });
        });
        $(document).on("click",'#add-menu-item',function() {
            var selector = $('#clone_menu .menu-item');
            var id = $('#dd-list .menu-item').last().data("id");
            var $copy  = selector.clone().appendTo('#dd-list');
            if (isNaN(id))
                id =0;

            $('#dd-list .menu-item').last().attr("data-id", ++id);

        });

    </script>
@endpush
