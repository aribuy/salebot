<?php
namespace App\Http\Controllers\Admin;
use Exception;
use App\Models\Country;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\DataTables\StaffDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StaffRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\Admin\StaffRepository;

class StaffController extends Controller
{
    protected $staff;

    protected $role;

    protected $permission;

    protected $user;

    public function __construct(StaffRepository $staff, RoleRepository $role, PermissionRepository $permission, UserRepository $user)
    {
        $this->staff      = $staff;
        $this->role       = $role;
        $this->permission = $permission;
        $this->user       = $user;
    }

    public function index(StaffDataTable $staffDataTable)
    {
        Gate::authorize('staffs.index');

        return $staffDataTable->render('backend.admin.staff.all-staff');
    }

    public function create()/*: \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application */
    {
        Gate::authorize('staffs.create');
        try {
            $permissions = $this->permission->all();
            $countries   = Country::all();
            $roles       = $this->role->staffRoll();
            $data        = [
                'permissions' => $permissions,
                'countries'   => $countries,
                'roles'       => $roles,
            ];

            return view('backend.admin.staff.add-staff', $data);
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function changeRole(Request $request)
    {
        Gate::authorize('staffs.change-role');
        $role_permissions = $this->role->get($request->role_id)->permissions;
        $permissions      = $this->permission->all();

        return view('backend.admin.staff.permissions', compact('permissions', 'role_permissions'))->render();
    }

    public function store(StaffRequest $request): \Illuminate\Http\JsonResponse
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
            $this->staff->store($request->all());

            DB::commit();
            Toastr::success(__('create_successful'));

            return response()->json([
                'success' => __('create_successful'),
                'route'   => route('staffs.index'),
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('staffs.edit');
        try {
            $permissions = Permission::all();
            $countries   = Country::all();
            $roles       = $this->role->staffRoll();
            $staff       = $this->staff->edit($id);
            $data        = [
                'permissions' => $permissions,
                'countries'   => $countries,
                'roles'       => $roles,
                'staff'       => $staff,
            ];

            return view('backend.admin.staff.edit-staff', $data);
        } catch (Exception $e) {
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

            $this->staff->update($request->all(), $id);

            DB::commit();
            Toastr::success(__('update_successful'));

            return response()->json([
                'success' => __('update_successful'),
                'route'   => route('staffs.index'),
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function destroy($id)
    {
        //
    }

    public function statusChange(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 400,
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->user->statusChange($request->all());
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
                'title'   => 'danger',
            ];

            return response()->json($data);
        }
    }

    public function StaffVerified($id): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::info(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $response = $this->user->userVerified($id);
            Toastr::success(__($response['message']));

            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return redirect()->back();
        }
    }

    public function StaffBanned($id): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::info(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $response = $this->user->userBan($id);
            Toastr::success(__($response['message']));

            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return redirect()->back();
        }
    }

    public function staffDelete($id): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        Gate::authorize('delete');
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $response = $this->user->userDelete($id);

            $data     = [
                'status'  => 'success',
                'message' => __($response['message']),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }
}
