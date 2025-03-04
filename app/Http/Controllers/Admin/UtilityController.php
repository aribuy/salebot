<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\UpdateTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class UtilityController extends Controller
{
    use UpdateTrait;

    public function serverInfo(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('server.info');

        return view('backend.admin.utility.server_info');
    }

    public function systemUpdate(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Gate::authorize('system.update');
        try {
            $url = 'https://license.spagreen.net/version-check';
            $fields   = [
                'item_id'               => '51330626',
                'activation_code'       => setting('activation_code'),
                'current_version'       => setting('current_version'),
            ];

            $response = false;
            if (config('app.beta_channel')):
                $url = 'https://license.spagreen.net/version-check-including-beta';
            endif;
            $request  = curlRequest($url, $fields);
            if (property_exists($request, 'status') && $request->status) {
                $response = $request->release_info;
            }
            if (is_bool($response)) {
                $latest_version    = setting('current_version');
                $is_old            = setting('current_version') < $latest_version;
                $next_version_code = 'v'.implode('.', str_split((int) setting('current_version')));
                $next_version      = (int) setting('current_version');
            } else {
                $latest_version    = $response->version;
                $is_old            = setting('current_version') < $latest_version;
                $next_version      = (int) $latest_version;
                $next_version_code = 'v'.implode('.', str_split($next_version));
            }

            $data     = [
                'response'          => $response,
                'latest_version'    => setting('current_version'),
                'is_old'            => $is_old,
                'next_version'      => $next_version,
                'next_version_code' => $next_version_code,
            ];
            return view('backend.admin.utility.system_update', $data);
        } catch (\Exception $e) {
            Toastr::error(preg_replace('/[^A-Za-z0-9 ]/', '', strip_tags($e->getMessage())));
            return back();
        }
    }

    public function downloadUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (isDemoMode()) {
                return response()->json([
                    'message' => __('this_function_is_disabled_in_demo_server'),
                    'type'    => __('Error').' !',
                    'class'   => 'danger',
                ]);
            }

            $update = $this->downloadUpdateFile($request->all());

            if (is_string($update)) {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('all:clear');
                return response()->json([
                    'message' => $update,
                    'type'    => __('Error').' !',
                    'class'   => 'danger',
                ]);
            }

            return response()->json([
                'type'    => __('Success').' !',
                'class'   => 'success',
                'message' => __('Update Successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type'    => __('Error').' !',
                'class'   => 'danger',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
