<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CurrencyDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Repositories\CurrencyRepository;
use App\Repositories\SettingRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class CurrencyController extends Controller
{
    protected $currency;

    protected $settings;

    public function __construct(CurrencyRepository $currency, SettingRepository $settings)
    {
        $this->currency = $currency;
        $this->settings = $settings;
    }

    public function index(CurrencyDataTable $dataTable)
    {
        Gate::authorize('currencies.index');
        return $dataTable->render('backend.admin.currency.all-currency');
    }

    public function create()
    {
        //
    }

    public function store(CurrencyRequest $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->currency->store($request->all());
            Artisan::call('all:clear');

            return response()->json(['success' => __('create_successful')]);
        } catch (Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('currencies.edit');
        try {
            $currency = $this->currency->get($id);
            $data     = [
                'id'            => $currency->id,
                'name'          => $currency->name,
                'symbol'        => $currency->symbol,
                'code'          => $currency->code,
                'exchange_rate' => $currency->exchange_rate,
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function update(CurrencyRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        try {

            $this->currency->update($request->all(), $id);
            Artisan::call('all:clear');

            return response()->json(['success' => __('update_successful')]);
        } catch (Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('currencies.destroy');
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->currency->delete($id);

            $data = [
                'status'  => 'success',
                'message' => __('delete_successful'),
                'title'   => __('success'),
            ];
            Artisan::call('all:clear');

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
            $this->currency->statusChange($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];
            Artisan::call('all:clear');

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

    public function setDefault($id)
    {

        Gate::authorize('currencies.default-currency');
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }

        try {

            $request = new \Illuminate\Http\Request();
            $request->setMethod('POST');
            $request->request->add(['default_currency' => $id]);
            Artisan::call('all:clear');

            $this->settings->update($request);
            Toastr::success(__('updated_successfully'));

            return back();
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function setCurrencyFormat(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('set.currency.format');
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $this->settings->update($request);
            Artisan::call('all:clear');
            Toastr::success(__('updated_successfully'));

            return back();
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }
}
