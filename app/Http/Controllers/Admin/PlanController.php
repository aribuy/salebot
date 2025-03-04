<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanRequest;
use App\Repositories\PlanRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    protected $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function index()
    {
        $data = [
            'plans' => $this->planRepository->all(),
        ];

        return view('backend.admin.plan.index', $data);
    }

    public function create(): View|Factory|RedirectResponse|Application
    {
        try {
            return view('backend.admin.plan.form');
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function store(PlanRequest $request): JsonResponse
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
            $this->planRepository->store($request->all());

            Toastr::success(__('create_successful'));

            DB::commit();
            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('plans.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }

    }

    public function edit($id): View|Factory|RedirectResponse|Application
    {
        try {
            $package = $this->planRepository->find($id);
            $data    = [
                'plan'       => $package,
                'stripe_key' => $this->planRepository->getPGCredential($id, 'stripe'),
                'paypal'     => $this->planRepository->getPGCredential($id, 'paypal'),
                'paddle'     => $this->planRepository->getPGCredential($id, 'paddle'),
                'razor_pay'  => $this->planRepository->getPGCredential($id, 'razor_pay'),
            ];

            return view('backend.admin.plan.form', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function update(PlanRequest $request, $id): JsonResponse
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
            $this->planRepository->update($request->all(), $id);
            Toastr::success(__('update_successful'));
            DB::commit();

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('plans.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
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
            $this->planRepository->destroy($id);
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

    public function statusChange(Request $request): JsonResponse
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
            $this->planRepository->status($request->all());
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

    public function PackageSubscribe($id, UserSubscriptionRepository $subscriptionRepository): JsonResponse
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
            $package                         = $this->planRepository->find($id);
            $user_subscribe                  = $subscriptionRepository->getUserSubscription(auth()->user()->id);
            $request['user_id']              = auth()->user()->id;
            $request['package_solutions_id'] = $package->id;
            $request['price']                = $package->price;
            $request['billing_period']       = $package->billing_period;
            $request['upload_limit']         = $package->upload_limit;
            $request['add_limit']            = $package->add_limit;
            $request['bundle']               = $package->bundle;
            $request['facilities']           = $package->facilities;
            if (! empty($user_subscribe)) {
                $request['billing_period'] = ($user_subscribe->billing_period + $package->billing_period);
                $subscriptionRepository->update(auth()->user()->id, $request);
            } else {
                $subscriptionRepository->store($request);
            }

            Toastr::success(__('subscription_successful'));
            $data                            = [
                'status'    => 'success',
                'message'   => __('subscription_successful'),
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
