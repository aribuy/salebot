<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WebsiteUniqueFeatureDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WebsiteUniqueFeatureRequest;
use App\Repositories\LanguageRepository;
use App\Repositories\SettingRepository;
use App\Repositories\WebsiteUniqueFeatureRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteUniqueFeatureController extends Controller
{
    protected $uniqueFeatureRepository;

    protected $language;

    protected $setting;

    public function __construct(WebsiteUniqueFeatureRepository $uniqueFeatureRepository, LanguageRepository $language, SettingRepository $setting)
    {
        $this->uniqueFeatureRepository = $uniqueFeatureRepository;
        $this->language                = $language;
        $this->setting                 = $setting;
    }

    public function index(WebsiteUniqueFeatureDataTable $dataTable)
    {
        return $dataTable->render('backend.admin.website.unique_feature.index');
    }

    public function create(Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->lang ?? app()->getLocale();
        $data = [
            'lang' => $lang,
        ];

        return view('backend.admin.website.unique_feature.create', $data);
    }

    public function store(WebsiteUniqueFeatureRequest $request): \Illuminate\Http\JsonResponse
    {

        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        DB::beginTransaction();
        try {
            $this->uniqueFeatureRepository->store($request);
            Toastr::success(__('create_successful'));

            DB::commit();

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('unique-feature.index'),
            ]);
        } catch (\Exception $e) {

            dd($e->getMessage());
            DB::rollBack();

            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id, Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $feature = $this->uniqueFeatureRepository->find($id);
            $lang    = $request->lang ?? app()->getLocale();
            $data    = [
                'lang'             => $lang,
                'feature_language' => $this->uniqueFeatureRepository->getByLang($id, $lang),
                'feature'          => $feature,
            ];

            return view('backend.admin.website.unique_feature.edit', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(WebsiteUniqueFeatureRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }

        DB::beginTransaction();
        try {
            $this->uniqueFeatureRepository->update($request, $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('unique-feature.index'),
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();

            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->uniqueFeatureRepository->destroy($id);
            Toastr::success(__('delete_successful'));
            $data = [
                'status'  => 'success',
                'message' => __('delete_successful'),
                'title'   => __('success'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => __('error'),
            ];

            return response()->json($data);
        }
    }

    public function statusChange(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }

        try {
            $this->uniqueFeatureRepository->status($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function imageUpdate(Request $request)
    {
        $request->validate([
            'unique_feature_image' => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
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
            $this->setting->update($request);
            Toastr::success(__('update_successful'));
            DB::commit();

            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(__('something_went_wrong_please_try_again'));

            return back();
        }
    }
}
