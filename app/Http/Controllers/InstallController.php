<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallRequest;
use App\Models\Setting;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use ZipArchive;

class InstallController extends Controller
{
    public function index()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return view('install.index');
        }
        if (config('app.app_installed')) {
            return redirect('/');
        }

        return view('install.index');
    }

    public function getInstall(InstallRequest $request): JsonResponse
    {
        ini_set('max_execution_time', 900); //900 seconds
        try {
            $host                = $request->host;
            $db_user             = $request->db_user;
            $db_name             = $request->db_name;
            $db_password         = $request->db_password;
            $activation_code     = $request->activation_code; //check for valid database connection
            try {
                $mysqli = @new \mysqli($host, $db_user, $db_password, $db_name);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => __('Please input valid database information.'),
                ]);
            }
            if (mysqli_connect_errno()) {
                return response()->json([
                    'error' => __('Please input valid database information.'),
                ]);
            }
            $mysqli->close(); // validate code
            $data['DB_HOST']     = $host;
            $data['DB_DATABASE'] = $db_name;
            $data['DB_USERNAME'] = $db_user;
            $data['DB_PASSWORD'] = $db_password;
            $verification        = validate_purchase($activation_code, $data);
            if ($verification === 'success') {
                session()->put('activation_code', $activation_code);

                return response()->json([
                    'success' => 'Activation Code & Database Connection Verified',
                ]);
            } elseif ($verification === 'connection_error') {
                return response()->json([
                    'error' => __('There is a problem to connect with SpaGreen server.Make sure you have active internet connection!'),
                ]);

            } elseif (! $verification) {
                return response()->json([
                    'error' => __('Something went wrong. Please try again.'),
                ]);
            } else {
                return response()->json([
                    'error' => $verification,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function final(InstallRequest $request): JsonResponse
    {
        try {
            $zip_file    = base_path('public/install/installer.zip');
            if (file_exists($zip_file)) {
                $zip = new ZipArchive;
                if ($zip->open($zip_file) === true) {
                    $zip->extractTo(base_path('/'));
                    $zip->close();
                } else {
                    return response()->json([
                        'type'  => 'error',
                        'error' => 'Installation files Not Found, Please Try Again',
                        'route' => route('install.initialize'),
                    ]);
                }
                unlink($zip_file);
            }
            $config_file = base_path('config.json');
            if (file_exists($config_file)) {
                $config = json_decode(file_get_contents($config_file), true);
            } else {
                return response()->json([
                    'type'  => 'error',
                    'error' => 'Config File Not Found, Please Try Again',
                    'route' => route('install.initialize'),
                ]);
            }
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->dataInserts($config, $request);
            $this->envUpdates();
            $this->demoDataImport();

            return response()->json([
                'type'    => 'success',
                'success' => 'Installation was Successful',
                'route'   => url('/'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function dataInserts($config, $request): void
    {
        $user             = User::find(1);
        $user->email      = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->password   = bcrypt($request->password);
        $user->save();

        $code             = Setting::where('title', 'activation_code')->first();

        if ($code) {
            $code->update([
                'value' => session()->get('activation_code'),
            ]);
        } else {
            Setting::create([
                'title' => 'activation_code',
                'value' => session()->get('activation_code'),
            ]);
        }

        if (isAppMode()) {
            $version      = $config['app_version'];
            $version_code = $config['app_version_code'];
        } else {
            $version      = $config['web_version'];
            $version_code = $config['web_version_code'];
        }

        $code             = Setting::where('title', 'version_code')->first();
        $version_no       = Setting::where('title', 'current_version')->first();

        if ($code) {
            $code->update([
                'value' => $version_code,
            ]);
        } else {
            Setting::create([
                'title' => 'version_code',
                'value' => $version_code,
            ]);
        }

        if ($version_no) {
            $version_no->update([
                'value' => $version,
            ]);
        } else {
            Setting::create([
                'title' => 'current_version',
                'value' => $version,
            ]);
        }

        if (arrayCheck('removed_directories', $config)) {
            foreach ($config['removed_directories'] as $directory) {
                File::deleteDirectory(base_path($directory));
            }
        }
    }

    protected function envUpdates(): void
    {
        envWrite('APP_URL', URL::to('/'));
        envWrite('APP_INSTALLED', true);
        Artisan::call('key:generate');
        Artisan::call('all:clear');
    }

    protected function demoDataImport(): void
    {
        try {
            DB::unprepared(file_get_contents(base_path('public/sql/demo_data.sql')));
        } catch (\Exception $e) {
        }
    }




    public function releaseForm()
    {
        if (!isDemoMode()) {
            abort(404);
        }

        return view('install.release');
    }

    public function createRelease(Request $request)
    {
        $request->validate([
            'latest_commit' => 'required',
            'old_commit'    => 'required',
            'prefix'        => 'required',
            'version'       => 'required',
        ]);

        try {
            $latest_commit  = $request->latest_commit;
            $old_commit     = $request->old_commit;
            $name           = $request->prefix;
            $version        = $request->version;
            $gitDiffCommand = "git diff --name-only $latest_commit $old_commit";
            $changedFiles   = shell_exec($gitDiffCommand);
            file_put_contents(base_path('release_creator.txt'), $changedFiles);
            $file           = base_path('release_creator.txt');
            $lines          = file($file);
            $data           = [];
            foreach ($lines as $line) {
                $data[] = $line;
            }
            $data           = array_filter($data);
            $data           = array_map('trim', $data);
            $data           = array_filter(array_unique(array_values($data)));
            $zip            = new ZipArchive;
            $release_name   = $name.'_release_v'.$version;
            $zip_file       = base_path("$release_name.zip");
            if ($zip->open($zip_file, ZipArchive::CREATE) === true) {
                foreach ($data as $file) {
                    if (file_exists(base_path($file))) {
                        $zip->addFile(base_path($file), $file);
                    }
                }
                $zip->close();
            }
            $script_url     = str_replace('admin/update-system', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

            $fields         = [
                'domain'        => urlencode($_SERVER['SERVER_NAME']),
                'version'       => $request->latest_version,
                'item_id'       => "51330626",
                'purchase_code' => urlencode(setting('activation_code')),
                'url'           => urlencode($script_url),
                'is_beta'       => config('app.dev_mode') ? 1 : 0,
            ]; 

            $_request        = curlRequest('https://desk.spagreen.net/verify-installation-v2', $fields);
            $zip_file       = $_request->release_zip_link;
            $file_path      = base_path('updater.zip');
            file_put_contents($file_path, file_get_contents($zip_file));
            File::delete([base_path('release_creator.txt'), $file_path]);
            Toastr::success('Release Created Successfully');

            return back();
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
