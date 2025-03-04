<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use App\Repositories\ClientRepository;
use Illuminate\Contracts\View\Factory;
use App\DataTables\Client\TeamDataTable;
use App\Repositories\Client\TeamRepository;
use App\Http\Requests\Admin\ClientStaffRequest;
use Illuminate\Contracts\Foundation\Application;

class TeamController extends Controller
{
    protected $client;

    protected $user;

    protected $teamRepo;

    public function __construct(ClientRepository $client, TeamRepository $teamRepo, UserRepository $user)
    {
        $this->client   = $client;
        $this->user     = $user;
        $this->teamRepo = $teamRepo;

    }

    public function index(TeamDataTable $dataTable)
    {
        try {
            return $dataTable->render('backend.client.team.index');
        } catch (\Exception $e) {

            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function create(Request $request)
    {
        try {
            return view('backend.client.team.create');
        } catch (Exception $e) {

            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function store(ClientStaffRequest $request, UserRepository $userRepository): JsonResponse
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
            $total_team             = User::where('client_id', auth()->user()->client_id)->where('status', 1)->count();
            $team_limit             = @auth()->user()->activeSubscription->team_limit;
            if ($total_team >= $team_limit) {
                $data = [
                    'status' => 'danger',
                    'error'  => __('insufficient_team_limit'),
                    'title'  => 'error',
                ];

                return response()->json($data);
            }

            $permissions            = [];

            foreach ($request->permissions ?? [] as $key => $value) {
                if ($value == 1) {
                    $permissions[] = $key;
                }
            }

            $request['permissions'] = $permissions;

            $this->teamRepo->store($request->all());

            DB::commit();
            Toastr::success(__('create_successful'));

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('client.team.index'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id): View|Factory|RedirectResponse|Application
    {
        try {

            $user = $this->user->find($id);
            if (auth()->user()->client_id !== $user->client_id) {
                Toastr::error(__('this_staff_does_not_belong_to_this_client'));

                return back();
            }

            $data = [
                'user' => $user,
            ];

            return view('backend.client.team.edit', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(ClientStaffRequest $request, $id): JsonResponse
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

            $permissions            = [];

            foreach ($request->permissions ?? [] as $key => $value) {
                if ($value == 1) {
                    $permissions[] = $key;
                }
            }

            $request['permissions'] = $permissions;

            $this->user->update($request->all(), $id);

            DB::commit();

            Toastr::success(__('update_successful'));

            return response()->json(['success' => __('update_successful'),  'route' => route('client.team.index')]);
        } catch (Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function payout(): View|Factory|RedirectResponse|Application
    {

        try {
            $id   = auth()->user()->id;
            $user = $this->user->find($id);
            $data = [
                'instructor' => $user->instructor,
            ];

            return view('backend.organization.payout.payout_method', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
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
        $user = User::find($request->id);
        if ($user->status == 0) {
            $total_team = User::where('client_id', auth()->user()->client_id)
                ->where('status', 1)
                ->count();
            $team_limit = auth()->user()->activeSubscription->team_limit;

            if ($total_team >= $team_limit) {
                $data = [
                    'status'  => 'danger',
                    'message' => __('insufficient_team_limit'),
                    'title'   => 'error',
                ];

                return response()->json($data);
            }
        }

        try {
            $this->teamRepo->statusChange($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }
}
