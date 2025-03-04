<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\ClientDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use App\Repositories\ClientRepository;
use App\Repositories\CountryRepository;
use App\Http\Requests\Admin\ClientRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;

class ClientController extends Controller
{
    protected $repo;

    protected $country;

    protected $userRepo;

    public function __construct(ClientRepository $repo, CountryRepository $country, UserRepository $userRepo)
    {
        $this->repo   = $repo;
        $this->country  = $country;
        $this->userRepo = $userRepo;
    }

    public function index(ClientDataTable $dataTable)
    {
        try {
            $data = [
                'client'           => $this->repo->clientStatus(),
                'approved_clients' => $this->repo->clientStatus(1),
                'users'            => $this->userRepo->totalUser(),
                'inactive_clients' => $this->repo->clientStatus('0'),
            ];

            return $dataTable->render('backend.admin.client.all_client', $data);

        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function create()
    {
        try {
            $countries = $this->country->all();
            $data      = [
                'countries' => $countries,
            ];
            return view('backend.admin.client.add_client', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }
   
    public function store(ClientRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        DB::beginTransaction();
        try {
            $this->repo->store($request->all());
            DB::commit();
            Toastr::success(__('create_successful'));
            return redirect()->route('clients.index');
        } catch (Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            Toastr::error('something_went_wrong_please_try_again');
            return back()->withInput();
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $countries = $this->country->all();
            $client    = $this->repo->find($id);
            $data      = [
                'countries' => $countries,
                'client'    => $client,
            ];
            return view('backend.admin.client.edit_client', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }

    public function update(ClientUpdateRequest $request, $id)
    {

        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        DB::beginTransaction();
        try { 
            $this->repo->update($request->all(), $id);

            DB::commit();
            Toastr::success(__('update_successful'));
            DB::commit();
            return redirect()->route('clients.index');
        } catch (Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('clients.delete');

        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->delete($id);

            $data = [
                'status'  => 'success',
                'message' => __('update_successful'),
                'title'   => __('success'),
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => $e->getMessage(),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function payment($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $client = $this->repo->find($id);
            $data   = [
                'client' => $client,
            ];

            return view('backend.admin.client.payout.payout_method', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
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
            $this->repo->statusChange($request->all());
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

    public function clientStaff(Request $request)
    {

        $clientId    = $request->input('client_id');

        $clientStaff = User::where('client_id', $clientId)->pluck('first_name', 'id')->toArray();

        return response()->json($clientStaff);
    }
}
