<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CustomNotificationDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\CustomNotificationRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomNotificationController extends Controller
{
    protected $notification;

    public function __construct(CustomNotificationRepository $notification)
    {
        $this->notification = $notification;
    }

    public function index(CustomNotificationDataTable $dataTable)
    {
        return $dataTable->render('backend.admin.custom_notification.index');
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.admin.custom_notification.create');
    }

    public function store(Request $request): JsonResponse
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
            'title'       => 'required',
            'description' => 'required',
        ]);

        try {
            if (! setting('onesignal_rest_api_key') || ! setting('onesignal_app_id')) {
                return response()->json([
                    'error'  => __('configure_onesignal'),
                    'status' => 'danger',
                    'title'  => __('error'),
                ]);
            }
            $this->notification->store($request->all());

            Toastr::success(__('create_successful'));

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('custom-notification.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id): JsonResponse
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
            $this->notification->destroy($id);
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
}
