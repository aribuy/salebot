<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\LanguageRepository;
use App\Repositories\PageRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\DataTables\WebsitePageDatatable;
use Illuminate\Support\Facades\DB;

class WebsitePageController extends Controller
{
    protected $page;

    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    public function index(WebsitePageDatatable $dataTable)
    {
        try {
            $data = [
                'pages' => $this->page->all([
                    'paginate' => setting('paginate'),
                ]),
            ];

            return $dataTable->render('backend.admin.website.page.index', $data);

        }catch (\Exception $e) {
            Toastr::error('');
            return back();
        }
    }

    public function create()
    {
        try {
            return view('backend.admin.website.page.create');
        }catch (\Exception $e) {
            Toastr::error('');
            return back();
        }
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'meta_description' => 'required',
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
            $this->page->store($request->all());
            Toastr::success(__('create_successful'));

            DB::commit();

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('pages.index'),
            ]);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '']);
        }
    }

    public function edit($id, LanguageRepository $language, Request $request)
    {
        try {
            $lang = $request->lang ?? app()->getLocale();
            $data = [
                'languages'     => $language->activeLanguage(),
                'lang'          => $lang,
                'page'          => $this->page->get($id),
                'page_language' => $this->page->getByLang($id, $lang),
            ];

            return view('backend.admin.website.page.edit', $data);
        }catch (\Exception $e) {
            Toastr::error('');
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
        $request->validate([
            'title'            => 'required',
            'meta_description' => 'required',
            'link'             => 'required|unique:pages,link,'.$id,
        ]);
        DB::beginTransaction();
        try {
            $this->page->update($request->all(), $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('pages.index'),
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '']);
        }
    }

    public function destroy($id)
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
            $this->page->destroy($id);
            Toastr::success(__('delete_successful'));
            $data = [
                'status'  => 'success',
                'message' => __('delete_successful'),
                'title'   => __('success'),
            ];

            return response()->json($data);
        }catch (\Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' =>  __('something_went_wrong_please_try_again'),
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
            $this->page->status($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        }catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' =>  __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }
}
