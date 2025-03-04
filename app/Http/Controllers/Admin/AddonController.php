<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\Log;

class AddonController extends Controller
{
    protected $installed_addon;

    public function index()
    {
        Gate::authorize('addon.index');
        try {
            $data = [
                'addons' => Addon::paginate(setting('paginate')),
            ];

            return view('backend.admin.addons.installed', $data);
        } catch (\Exception $e) {
        }
    }

    public function addonMarketPlace()
    {
        return view('backend.admin.addons.available');
    }   

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'activation_code'  => 'required',
            'addon_zip_file' => 'required|mimes:zip',
        ]);

        if (config('app.demo_mode')) {
            return response()->json([
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ]);
        }

        $rand_str_dir = Str::random(10);
        $current_version = setting('current_version');

        try {
            // Simulate purchase code verification (replace with actual verification)
            $verify_code = 'verified';
            if ($verify_code == 'unverified') {
                return response()->json(['error' => 'There is a problem with your purchase code. Please contact Envato support team.']);
            }

            // Create addons directory if it doesn't exist
            $dir = 'public/addons';
            if (!is_dir(base_path($dir))) {
                mkdir(base_path($dir), 0777, true);
            }

            // Check if ZipArchive class exists
            if (!class_exists('ZipArchive')) {
                return response()->json(['error' => 'ZipArchive class not found']);
            }

            // Open the uploaded zip file
            $zip = new ZipArchive();
            if ($zip->open($request->addon_zip_file->path()) === true) {
                $zip->extractTo(base_path('public/addons/' . $rand_str_dir . '/'));
                $zip->close();
            } else {
                return response()->json(['error' => 'Unable to open file, please try again']);
            }

            // Get Addon class
            $get_addon_class = glob(base_path('public/addons/' . $rand_str_dir . '/*Addon.php'));
            if (empty($get_addon_class)) {
                File::deleteDirectory(base_path('public/addons/' . $rand_str_dir));
                return response()->json(['error' => 'Addon.php file not found']);
            }

            $get_addon_class = explode('/', $get_addon_class[0]);
            $get_addon_class = explode('.', end($get_addon_class));
            $this->installed_addon = substr($get_addon_class[0], 0, -5);

            // Read config.json
            $config_path = base_path("public/addons/$rand_str_dir/config.json");
            if (!File::exists($config_path)) {
                File::deleteDirectory(base_path('public/addons/' . $rand_str_dir));
                return response()->json(['error' => 'config.json file not found']);
            }
            $read_json = file_get_contents($config_path);
            $decoded_json = json_decode($read_json, true);
            // Check for JSON decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                $json_error = json_last_error_msg();
                return response()->json(['error' => 'Invalid JSON in config file: ' . $json_error]);
            }
            $addon_name = $decoded_json['name'];
            $addon_description = $decoded_json['description'];
            $addon_identifier = $decoded_json['addon_identifier'];
            $activation_code = $request->activation_code;
            $addon_version = $decoded_json['version'];
            $cms_version = $decoded_json['required_cms_version'];
            $app_version = $decoded_json['required_app_version'];
            // Check app version compatibility
            if (isAppMode() && $current_version < $app_version) {
                File::deleteDirectory(base_path('public/addons/' . $rand_str_dir));
                return response()->json(['error' => __('please_update_your_app_version_to_install_this_addon')]);
            }
            if ($current_version < $cms_version) {
                File::deleteDirectory(base_path('public/addons/' . $rand_str_dir));
                return response()->json(['error' => __('please_update_your_cms_version_to_install_this_addon')]);
            }

            // Move CSS and JS folders to public directory
            // $addonPath = base_path("public/addons/{$rand_str_dir}");
            // $cssPath = "{$addonPath}/css";
            // $jsPath = "{$addonPath}/js";
            // if (is_dir($cssPath)) {
            //     File::copyDirectory($cssPath, public_path("css"));
            // }
            // if (is_dir($jsPath)) {
            //     File::copyDirectory($jsPath, public_path("js"));
            // }

            $addon_exist = Addon::where('addon_identifier', $addon_identifier)->first();


            if ($addon_exist) {
                File::deleteDirectory(base_path('app/Addons/' . $this->installed_addon));
                Addon::where('addon_identifier', $addon_identifier)->update([
                    'activation_code' => $activation_code,
                    'version' => $addon_version,
                    'image' => '',
                ]);
            } else {
                Addon::create([
                    'name' => $addon_name,
                    'description' => $addon_description,
                    'addon_identifier' => $addon_identifier,
                    'activation_code' => $activation_code,
                    'version' => $addon_version,
                    'image' => '',
                    'status' => 1,
                ]);
            }

            // Attempt to move the addon directory
            $source = base_path('public/addons/' . $rand_str_dir);
            $destination = base_path('app/Addons/' . $this->installed_addon);
            if (!rename($source, $destination)) {
                throw new \Exception("Failed to move addon directory from $source to $destination");
            }
            // Delete the temporary addon directory
            File::deleteDirectory(base_path('public/addons/' . $rand_str_dir));
            // Run migrations
            Artisan::call('migrate', [
                '--path' => "app/Addons/{$this->installed_addon}/migrations",
                '--force' => true,
            ]);
            // Clear caches
            Artisan::call('all:clear');
            Toastr::success(__('addon_installed_successfully'));
            return response()->json(['success' => __('addon_installed_successfully')]);
        } catch (\Exception $e) {
           

            // Cleanup on failure
            if (is_dir(base_path('public/addons/' . $rand_str_dir))) {
                File::deleteDirectory(base_path('public/addons/' . $rand_str_dir));
            }
            if (is_dir(base_path('app/Addons/' . $this->installed_addon))) {
                File::deleteDirectory(base_path('app/Addons/' . $this->installed_addon));
            }
            dd($e->getMessage());
            // Log the error
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
     
    }


    public function valid_activation_code($activation_code = '')
    {
        $activation_code = urlencode($activation_code);
        $verified        = 'unverified';
        if (! empty($activation_code) && $activation_code != '' && strlen($activation_code) > 24) {
            $url      = 'https://api.envato.com/v3/market/author/sale?code='.$activation_code;
            $ch       = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Envato API Wrapper PHP)');

            $header   = [];
            $header[] = 'Content-length: 0';
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: Bearer 5CZXrrM34RPf7ukUzCKqod2BAcQJNKE6';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $data     = curl_exec($ch);
            curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if (! empty($data)) {
                $result = json_decode($data, true);
                if (isset($result['buyer']) && isset($result['item']['id'])) {
                    return $result;
                }
            }
        }

        return $verified;
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
            $key         = Addon::findOrfail($request->id);
            $key->status = $request->status;
            $key->save();
            Artisan::call('all:clear');
            $data        = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
                'reload'  => '1',
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
}
