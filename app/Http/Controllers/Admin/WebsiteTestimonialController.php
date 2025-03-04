<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WebsiteTestimonialDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WebsiteTestimonialRequest;
use App\Repositories\LanguageRepository;
use App\Repositories\WebsiteTestimonialRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteTestimonialController extends Controller
{
    protected $testimonialRepository;

    public function __construct(WebsiteTestimonialRepository $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    public function index(WebsiteTestimonialDataTable $dataTable)
    {
        return $dataTable->render('backend.admin.website.testimonial.index');
    }

    public function create(Request $request, LanguageRepository $language): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {

        $lang = $request->lang ?? app()->getLocale();
        $data = [
            'lang' => $lang,
        ];

        return view('backend.admin.website.testimonial.create', $data);
    }

    public function store(WebsiteTestimonialRequest $request): \Illuminate\Http\JsonResponse
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
            $this->testimonialRepository->store($request);
            Toastr::success(__('create_successful'));

            DB::commit();

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('testimonials.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id, LanguageRepository $language, Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $testimonial = $this->testimonialRepository->find($id);
            $lang        = $request->lang ?? app()->getLocale();
            $data        = [
                'lang'                 => $lang,
                'testimonial_language' => $this->testimonialRepository->getByLang($id, $lang),
                'testimonial'          => $testimonial,
            ];

            return view('backend.admin.website.testimonial.edit', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(WebsiteTestimonialRequest $request, $id): \Illuminate\Http\JsonResponse
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
            $this->testimonialRepository->update($request, $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('testimonials.index'),
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
            $this->testimonialRepository->destroy($id);
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
            $this->testimonialRepository->status($request->all());
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
