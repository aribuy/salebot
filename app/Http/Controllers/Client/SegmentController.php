<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Client\SegmentDataTable;
use App\Http\Requests\Client\SegmentsRequest;
use App\Repositories\Client\SegmentRepository;

class SegmentController extends Controller
{
    protected $segmentsRepo;

    public function __construct(SegmentRepository $segmentsRepo)
    {
        $this->segmentsRepo = $segmentsRepo;
    }

    public function index(SegmentDataTable $segmentsDataTable)
    {
        return $segmentsDataTable->render('backend.client.whatsapp.segments.index');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
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
            'title' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $requestData = array_merge($request->all(), ['client_id' => Auth::user()->client_id]);
            $this->segmentsRepo->store($requestData);
            DB::commit();
            Toastr::success(__('create_successful'));

            return response()->json(['success' => __('create_successful')]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $segments = $this->segmentsRepo->find($id);
            $data     = [
                'segments' => $segments,
            ];

            return view('backend.client.whatsapp.segments.edit', $data);
        } catch (Exception $e) {

            if (config('app.debug')) {
                dd($e->getMessage());            
            }            
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(SegmentsRequest $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $this->segmentsRepo->update($request->all(), $id);
            Toastr::success(__('update_successful'));

            return redirect()->route('client.segments.index');
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back()->withInput();
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
            $this->segmentsRepo->statusChange($request->all());
            $data = [
                'status'  => 'success',
                'message' => __('update_successful'),
                'title'   => 'success',
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

    public function delete($id): \Illuminate\Http\JsonResponse
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
            $this->segmentsRepo->destroy($id);
            Toastr::success(__('delete_successful'));
            $data = [
                'status'    => 'success',
                'message'   => __('delete_successful'),
                'title'     => __('success'),
                'is_reload' => true,
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
}
