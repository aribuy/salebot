<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WebsiteAdvantageDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\LanguageRepository;
use App\Repositories\WebsiteAdvantageRepository;
use App\Traits\ImageTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteAdvantageController extends Controller
{
    use ImageTrait;

    protected $advantageRepository;

    protected $language;

    public function __construct(WebsiteAdvantageRepository $advantageRepository, LanguageRepository $language)
    {
        $this->advantageRepository = $advantageRepository;
        $this->language            = $language;
    }

    public function index(WebsiteAdvantageDataTable $dataTable)
    {
        return $dataTable->render('backend.admin.website.advantage.index');
    }

    public function create(Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $lang = $request->lang ?? app()->getLocale();
        $data = [
            'lang' => $lang,
        ];

        return view('backend.admin.website.advantage.create', $data);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'image' => 'mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
        ]);

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
            $this->advantageRepository->store($request);
            Toastr::success(__('create_successful'));

            DB::commit();

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('advantage.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id, LanguageRepository $language, Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $advantage = $this->advantageRepository->find($id);
            $lang      = $request->lang ?? app()->getLocale();
            $data      = [
                'lang'               => $lang,
                'advantage_language' => $this->advantageRepository->getByLang($id, $lang),
                'advantage'          => $advantage,
            ];

            return view('backend.admin.website.advantage.edit', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
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
            $this->advantageRepository->update($request, $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('advantage.index'),
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
            $this->advantageRepository->destroy($id);
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
            $this->advantageRepository->status($request->all());
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
