<?php

namespace App\Http\Controllers\Admin\WebsiteSetting;

use App\Http\Controllers\Controller;
use App\Repositories\LanguageRepository;
use App\Repositories\SettingRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HeaderSettingController extends Controller
{
    protected $setting;

    protected $language;

    public function __construct(SettingRepository $setting, LanguageRepository $language)
    {
        $this->setting  = $setting;
        $this->language = $language;
    }
    public function headerMenu(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages     = app('languages');
        $lang          = $request->site_lang ? $request->site_lang : App::getLocale();
        $menu_language = headerFooterMenu('header_menu', $lang);

        return view('backend.admin.website.menu', compact('languages', 'lang', 'menu_language'));
    }

    public function updateHeaderMenu(Request $request): \Illuminate\Http\JsonResponse
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
                        ];
                        $parent++;
                    } else {
                        $menu[count($menu) - 1][] = [
                            'label'     => $request['label'][$i],
                            'url'       => getArrayValue($i, $request['url']),
                        ];
                    }

                }

                foreach ($request->label as $key => $label) {
                    $data[$key]['label'] = $request['label'][$key];
                    $data[$key]['url'] = $request['url'][$key];
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

                if ($this->setting->update($request)) {
                    Toastr::success(__('Menu Updated Successfully'));
                    $data = [
                        'success' => __('Menu Updated Successfully'),
                    ];

                    return response()->json($data);
                } else {
                    Toastr::error(__('something_went_wrong_please_try_again'));
                    $data = [
                        'error' => __('something_went_wrong_please_try_again'),
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
        }catch (\Exception $e) {
            Toastr::error(__('something_went_wrong_please_try_again'));
        }
    }
}
