<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionRequest;
use App\Repositories\Client\SubscriptionRepository;
use App\Repositories\ClientRepository;
use App\Repositories\PlanRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    protected $subscriptionRepository;

    protected $planRepository;

    protected $client;

    public function __construct(SubscriptionRepository $subscriptionRepository, ClientRepository $client, PlanRepository $planRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->planRepository         = $planRepository;
        $this->client                 = $client;
    }

    public function PackageSubscribeList(SubscriptionDataTable $dataTable)
    {

        $data = [
            'clients' => $this->client->activeClient(),
            'plans'   => $this->planRepository->all(),
        ];

        return $dataTable->render('backend.admin.subscription.index', $data);
    }

    public function subscribeListStatus(Request $request, $id)
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
            $this->subscriptionRepository->subscribeListStatus($request->all(), $id);

            $data = [
                'status'  => 'success',
                'message' => __('status_update_successfully'),
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

    public function stopRecurring($id): JsonResponse
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
            $this->subscriptionRepository->stopRecurring($id);

            $data = [
                'status'    => 'success',
                'message'   => __('recurring_stopped_successfully'),
                'title'     => __('success'),
                'is_reload' => false,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'    => 'success',
                'message'   => $e->getMessage(),
                'title'     => __('error'),
                'is_reload' => false,
            ];

            return response()->json($data);
        }
    }

    public function addCredit(Request $request)
    {
        if (isDemoMode()) {
            return response()->json([
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ]);
        }

        try {
            $subscriptionId = $request->subscription_id;
            $newLimits      = [
                'new_contacts_limit'     => $request->new_contacts_limit,
                'new_campaigns_limit'    => $request->new_campaigns_limit,
                'new_conversation_limit' => $request->new_conversation_limit,
                'new_team_limit'         => $request->new_team_limit,
            ];

            $this->subscriptionRepository->updateSubscriptionLimits($subscriptionId, $newLimits);

            return Redirect::route('packages.subscribe-list')->with('success', __('update_successful'));
        } catch (Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function addValidity(Request $request, $id): JsonResponse
    {
        if (isDemoMode()) {
            return response()->json([
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ]);
        }
        try {
            $this->subscriptionRepository->updateValidity($request->all(), $id);

            return response()->json([
                'success' => __('validity_added_successfully'),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function addSubscription(SubscriptionRequest $request)
    {
        if (isDemoMode()) {
            return response()->json([
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ]);
        }
        try {
            $trx_id = 'offline-'.$request->transaction_id;
            $plan   = $this->planRepository->find($request->plan_id);
            $this->subscriptionRepository->store($request, $plan, $trx_id, '', true);

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('packages.subscribe-list'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }
}
