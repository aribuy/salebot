<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WebsitePartnerLogoDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WebsitePartnerLogoRequest;
use App\Repositories\LanguageRepository;
use App\Repositories\WebsitePartnerLogoRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsitePartnerLogoController extends Controller
{
    protected $websitePartnerLogoRepository;

    protected $language;

    public function __construct(WebsitePartnerLogoRepository $websitePartnerLogoRepository, LanguageRepository $language)
    {
        $this->websitePartnerLogoRepository = $websitePartnerLogoRepository;
        $this->language                     = $language;
    }

    public function index(WebsitePartnerLogoDataTable $dataTable)
    {
        return $dataTable->render('backend.admin.website.partner_logo.index');
    }

    public function create(Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->lang ?? app()->getLocale();
        $data = [
            'lang' => $lang,
        ];

        return view('backend.admin.website.partner_logo.create', $data);
    }

    public function store(WebsitePartnerLogoRequest $request): \Illuminate\Http\JsonResponse
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
            $this->websitePartnerLogoRepository->store($request);
            Toastr::success(__('create_successful'));

            DB::commit();

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('partner-logo.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $data = [
                'status'  => 400,
                'message' =>  __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function edit($id, Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $partner_logo = $this->websitePartnerLogoRepository->find($id);
            $lang         = $request->lang ?? app()->getLocale();
            $data         = [
                'lang'                  => $lang,
                'partner_logo_language' => $this->websitePartnerLogoRepository->getByLang($id, $lang),
                'partner_logo'          => $partner_logo,
            ];

            return view('backend.admin.website.partner_logo.edit', $data);
        } catch (\Exception $e) {
            Toastr::error( __('something_went_wrong_please_try_again'));

            return back();
        }
    }

    public function update(WebsitePartnerLogoRequest $request, $id): \Illuminate\Http\JsonResponse
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
            $this->websitePartnerLogoRepository->update($request, $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('partner-logo.index'),
            ]);
        } catch (\Exception $e) {
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
            $this->websitePartnerLogoRepository->destroy($id);
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
            $this->websitePartnerLogoRepository->status($request->all());
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
}
