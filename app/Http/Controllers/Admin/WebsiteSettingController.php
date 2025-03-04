<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSectionRequest;
use App\Http\Requests\Admin\ThemeOptionRequest;
use App\Repositories\LanguageRepository;
use App\Repositories\SettingRepository;
use App\Traits\ImageTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;

class WebsiteSettingController extends Controller
{
    use ImageTrait;

    protected $setting;

    protected $language;

    public function __construct(SettingRepository $setting, LanguageRepository $language)
    {
        $this->setting  = $setting;
        $this->language = $language;
    }

    public function themeOptions()
    {
        $folderNames= [];
        $folderPath = resource_path('views/website/themes');

        // Check if the directory exists
        if (File::exists($folderPath)) {
            // Get all directories inside the themes directory
            $folderNames = File::directories($folderPath);

            // Extract just the folder names
            $folderNames = collect($folderNames)->map(function ($folder) {
                return basename($folder);
            });

        } else {
            // Directory doesn't exist
            $folderNames = [];
        }

        $data = [
            'foldernames' => $folderNames
        ];

        return view('backend.admin.website.theme_options',$data);
    }

    public function updateThemesOptions(ThemeOptionRequest $request): \Illuminate\Http\JsonResponse
    {
        // dd($request->all());
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {
            Artisan::call('storage:link');

            $this->setting->update($request);


            DB::commit();

            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function menuSection(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.menu', compact('lang'));
    }

    public function updateMenuSection(Request $request): \Illuminate\Http\JsonResponse
    {

        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {

            $this->setting->update($request);

            Artisan::call('storage:link');

            DB::commit();

            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function sectionTitleSubtitle(Request $request)
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.section_title_subtitle', compact('lang'));
    }

    public function updateSectionTitleSubtitle(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function seo(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('website_setting.seo');
        try {
            $data = [
                'languages' => $this->language->all(),
                'lang'      => $request->lang == '' ? app()->getLocale() : $request->lang,
            ];

            return view('backend.admin.website.seo', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function saveSeoSetting(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('website_setting.seo');
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function google(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('website_setting.google_setup');
        try {
            return view('backend.admin.website.google_setup');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function saveGoogleSetup(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('website_setting.google_setup');
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'tracking_code'      => 'required_if:is_google_analytics_activated,==,1',
            'recaptcha_site_key' => 'required_if:is_recaptcha_activated,==,1',
            'recaptcha_secret'   => 'required_if:is_recaptcha_activated,==,1',
        ]);
        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function customCss(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('website_setting.custom_css');
        try {
            return view('backend.admin.website.custom_css');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function customJs(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('website_setting.gdpr');
        try {
            return view('backend.admin.website.custom_js');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function saveCustomCssAndJs(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('website_setting.custom_css');
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function fbPixel(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('website_setting.fb_pixel');
        try {
            return view('backend.admin.website.fb_pixel');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function saveFbPixel(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('website_setting.fb_pixel');
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'facebook_pixel_id' => 'required_if:is_facebook_pixel_activated,==,1',
        ]);
        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function headerFooter(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.admin.website.header_footer');
    }

    public function updateHeaderFooter(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function heroSection(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = app('languages');
        $lang      = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.hero', compact('languages', 'lang'));
    }

    public function updateHeroSection(HeroSectionRequest $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function aiChatSection(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.ai_chat', compact('lang'));
    }

    public function updateAiChatSection(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'qr_image' => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
        ]);
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {

            $data = $this->setting->update($request);

            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }

    public function ctaSection(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.cta', compact('lang'));
    }

    public function updateCTASection(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }



    public function counter(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.counter', compact('lang'));
    }

    public function updateCounter(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {

            $this->setting->update($request);

            Artisan::call('storage:link');

            DB::commit();

            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }


    public function service(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();
        
        return view('backend.admin.website.service', compact('lang'));
    }

    public function serviceUpdate(Request $request): \Illuminate\Http\JsonResponse
    {

        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {

            $this->setting->update($request);

            Artisan::call('storage:link');

            DB::commit();

            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }


    
    public function growthSection(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = app('languages');
        $lang      = $request->site_lang ? $request->site_lang : App::getLocale();
        return view('backend.admin.website.growth', compact('languages', 'lang'));
    }

    public function updateGrowthSection(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        try {
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            $data = [
                'success' => __('update_successful'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];

            return response()->json($data);
        }
    }



}
