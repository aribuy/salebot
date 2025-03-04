<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Timezone;
use App\Rules\AppIdRule;
use Illuminate\Http\Request;
use App\Rules\PhoneNumberIdRule;
use App\Rules\UniqueAccessToken;
use App\Http\Controllers\Controller;
use App\Rules\BusinessAccountIdRule;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\ClientRepository;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Client\ClientSettingRepository;
use App\Http\Requests\Admin\ClientUpdateRequest;

class ClientSettingController extends Controller
{
    protected $repo;

    protected $client;

    protected $country;

    public function __construct(ClientSettingRepository $repo, ClientRepository $client, CountryRepository $country)
    {
        $this->repo    = $repo;
        $this->client  = $client;
        $this->country = $country;
    }
 
    // WhatsApps Setting
    public function whatsAppSettings(Request $request)
    {
        return view('backend.client.setting.whatsApp');
    }

    public function whatsAppSettingUpdate(Request $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        $clientId = auth()->user()->client->id;
        $rules = [
            'access_token' => ['required', 'string', new UniqueAccessToken($clientId)],
            'phone_number_id' => ['nullable', 'string', new PhoneNumberIdRule($clientId)],
            'business_account_id' => ['required', 'string', new BusinessAccountIdRule($clientId)],
            'app_id' => ['nullable', 'string', new AppIdRule($clientId)],
        ];
        $messages = [
            'access_token.required' => __('access_token_is_required'),
            'business_account_id.required' => __('business_account_id_is_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $result = $this->repo->whatsAppSettingUpdate($request);
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }
        Artisan::call('all:clear');

        return back()->with($result->redirect_class, $result->message);
    }

    public function whatsAppsync(Request $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        return $this->repo->whatsAppsync($request);
    }

    public function removeWhatsAppToken(Request $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        return $this->repo->removeWhatsAppToken($request, $id);
    }


    // Telegram Setting
    public function telegramSettings(Request $request)
    {
        return view('backend.client.setting.telegram');
    }

    public function telegramUpdate(Request $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        $request->validate([
            'access_token' => 'required',
        ]);
        $result = $this->repo->telegramUpdate($request);
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }

        return back()->with($result->redirect_class, $result->message);
    }

    public function removeTelegramToken(Request $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        return $this->repo->removeTelegramToken($request, $id);
    }

    
    public function telegramSettingsync($id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        return $this->repo->telegramSettingsync($id);
    }


    public function billingDetails(Request $request)
    {
        $data = [
            'client' => $this->client->find(auth()->user()->client_id),
        ];

        return view('backend.client.setting.billing_details', $data);
    }

    public function storeBillingDetails(Request $request, $id)
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
            $this->repo->billingDetailsupdate($request, $id);
            Toastr::success(__('update_successful'));

            return back();
        } catch (Exception $e) {
            Toastr::error(__('something_went_wrong_please_try_again'));

            return back();
        }
    }

    public function generalSettings(Request $request)
    {
        $id   = auth()->user()->client_id;
        $data = [
            'client'     => $this->client->find($id),
            'countries'  => $this->country->all(),
            'time_zones' => Timezone::all(),
        ];

        return view('backend.client.setting.general', $data);
    }


    public function api(Request $request)
    {
        return view('backend.client.setting.api');
    }

    public function update_api(Request $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }

        $result = $this->repo->update($request);
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }

        return back()->with($result->redirect_class, $result->message);
    }

    public function updateGeneralSettings(ClientUpdateRequest $request, $id)
    {

        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $this->client->update($request->all(), $id);
            Toastr::success(__('update_successful'));

            return redirect()->route('client.general.settings');
        } catch (Exception $e) {
            Toastr::error($e->getMessage());
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            return back()->withInput();
        }
    }

    public function aiWriterSetting()
    {
        return view('backend.client.setting.ai_writer_setting');
    }
}
