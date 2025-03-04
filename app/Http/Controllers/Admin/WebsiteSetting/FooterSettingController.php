<?php

namespace App\Http\Controllers\Admin\WebsiteSetting;

use App\Http\Controllers\Controller;
use App\Repositories\LanguageRepository;
use App\Repositories\SettingRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FooterSettingController extends Controller
{
    protected $setting;

    protected $language;

    public function __construct(SettingRepository $setting, LanguageRepository $language)
    {
        $this->setting  = $setting;
        $this->language = $language;
    }

    public function footerContent()
    {
        return redirect()->route('footer.primary-content');
    }

    public function primaryContentSetting(Request $request)
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.footer_content.primary_content', compact('lang'));
    }


    public function paymentbannerSetting()
    {
        return view('backend.admin.website.footer_content.payment_banner');
    }

    public function usefulLinkSetting(Request $request)
    {
        $languages     = app('languages');
        $lang          = $request->site_lang ? $request->site_lang : App::getLocale();

        $menu_language = headerFooterMenu('footer_useful_link_menu', $lang);

        return view('backend.admin.website.footer_content.useful_link', compact('languages', 'lang', 'menu_language'));
    }

    public function quickLinkSetting(Request $request)
    {
        $languages     = app('languages');
        $lang          = $request->site_lang ? $request->site_lang : App::getLocale();
        $menu_language = headerFooterMenu('footer_quick_link_menu', $lang);

        return view('backend.admin.website.footer_content.quick_link', compact('languages', 'lang', 'menu_language'));
    }

    public function copyrightSetting(Request $request)
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.footer_content.copyright', compact('lang'));
    }

   
    public function updateSetting(Request $request): \Illuminate\Http\JsonResponse
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
                'error' => __('an_unexpected_error_occurred_please_try_again_later.'),
            ];

            return response()->json($data);
        }
    }

    public function menuUpdate(Request $request): \Illuminate\Http\JsonResponse
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
            if ($request->has('label')) {
                $menu = [];
                $parent = 0;
                for ($i = 0; $i < count($request->label); $i++) {
                    if ($request['menu_lenght'][$i] == 1) {
                        $menu[] = [
                            'label'     => $request['label'][$i],
                            'url'       => getArrayValue($i, $request['url']),
                            'mega_menu' => getArrayValue($i, $request['mega_menu_position'])
                        ];
                        $parent++;
                    } else {
                        $menu[count($menu) - 1][] = [
                            'label'     => $request['label'][$i],
                            'url'       => getArrayValue($i, $request['url']),
                            'mega_menu' => getArrayValue($i, $request['mega_menu_position'])
                        ];
                    }

                }

                foreach ($request->label as $key => $label) {
                    $data[$key]['label'] = $request['label'][$key];
                    $data[$key]['url'] = $request['url'][$key];
                    $data[$key]['mega_menu'] = @$request['mega_menu_position'][$key];
                }

                if ($request->has('menu_name')) {
                    $request[$request['menu_name']] = $menu;
                } else {
                    $request['footer_useful_link_menu'] = $menu;
                }

                $request['site_lang'] = $request->lang ?: app()->getLocale();
                unset($request['label']);
                unset($request['url']);
                unset($request['menu_lenght']);
                unset($request['menu_name']);
                unset($request['mega_menu_position']);

                if ($this->setting->update($request)) {
                    Toastr::success(__('updated_successfully'));
                    $data = [
                        'success' => __('updated_successfully'),
                    ];

                    return response()->json($data);
                } else {
                    Toastr::error(__('something_went_wrong_please_try_again'));
                    $data = [
                        'error' => __('an_unexpected_error_occurred_please_try_again_later.'),
                    ];

                    return response()->json($data);
                }
            } else {
                Toastr::error(__('No Menu Found'));
                $data = [
                    'error' => __('No Menu Found'),
                ];

                return response()->json($data);
            }
        } catch (\Exception $e) {
            Toastr::error(__('something_went_wrong_please_try_again'));
        }
    }



    public function socialLink(Request $request)
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.footer_content.social-link', compact('lang'));
    }
     

    public function newsletterSetting(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->site_lang ? $request->site_lang : App::getLocale();

        return view('backend.admin.website.footer_content.newsletter', compact('lang'));
    }

  

}
