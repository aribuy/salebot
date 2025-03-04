<?php
namespace App\Repositories;
use Pusher\Pusher;
use App\Models\Setting;
use App\Traits\ImageTrait;
use Pusher\PusherException;
use App\Traits\RepoResponse;
use App\Events\TestPusherEvent;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class SettingRepository
{
    use ImageTrait,RepoResponse,SendNotification;

    public function update($request): bool
    {
        $site_lang = $request->site_lang ?? 'en';

        foreach ($request->except('_token', '_method', 'site_lang', 'mobile_app', 'chat_messenger', 'countries', 'r') as $key => $value) {
            if ($key == 'default_language') {
                $setting = Setting::where('title', $key)->first();
            } else {
                if (isset($site_lang) && in_array($key, get_yrsetting('setting_by_lang'))) {
                    $setting = Setting::where('title', $key)->where('lang', $site_lang)->first();
                } else {
                    $setting = Setting::where('title', $key)->where('lang', 'en')->first();
                }
            }

            if (in_array($key, get_yrsetting('setting_image'))) {

                if (! blank($setting)) {
                    $this->deleteImage(setting($key));
                }

                $response = $this->saveImage($request->file($key), $key);

                $value    = serialize($response['images']);
            }

            if (in_array($key, get_yrsetting('setting_array'))) {
                $value = serialize($value);
            }

            if (blank($setting)) {
                $setting        = new Setting();
                $setting->title = $key;
            }

            if (blank($setting)) {
                $setting        = new Setting();
                $setting->title = $key;
                if (isset($site_lang) && in_array($key, get_yrsetting('setting_by_lang'))) {
                    $setting->lang = $site_lang;
                } else {
                    $setting->lang = 'en';
                }
                $setting->value = $value;
            } else {
                if (isset($site_lang) && in_array($key, get_yrsetting('setting_by_lang'))) {
                    $setting->lang = $site_lang;
                } else {
                    $setting->lang = 'en';
                }
                $setting->value = $value;
            }

            $setting->save();
        }
        Cache::flush();

        if ($request->has('system_name')) {
            $system_name = Setting::where('title', 'system_name')->where('lang', config('app.locale'))->first();
            if (! blank($system_name)) {
                envWrite('APP_NAME', $system_name->value);
            } else {
                $system_name = Setting::where('title', 'system_name')->first();
                if (! blank($system_name)) {
                    envWrite('APP_NAME', $system_name->value);
                }
            }
        }

        if ($request->has('is_cache_enabled')) {
            if (setting('is_cache_enabled') == 'enable') {
                if (setting('default_cache') == 'redis') {
                    envWrite('CACHE_DRIVER', 'redis');
                    envWrite('REDIS_CLIENT', 'predis');
                    envWrite('REDIS_HOST', setting('redis_host'));
                    envWrite('REDIS_PASSWORD', setting('redis_password'));
                    envWrite('REDIS_PORT', setting('redis_port'));
                } else {
                    envWrite('CACHE_DRIVER', 'file');
                }
            } else {
                envWrite('CACHE_DRIVER', 'file');
            }
        }
        if ($request->has('default_storage')) {
            if ($request->default_storage == 'aws_s3') {
                $aws_url = 'http://'.setting('aws_bucket').'.s3.'.setting('aws_default_region').'.amazonaws.com';

                envWrite('AWS_ACCESS_KEY_ID', setting('aws_access_key_id'));
                envWrite('AWS_SECRET_ACCESS_KEY', setting('aws_secret_access_key'));
                envWrite('AWS_DEFAULT_REGION', setting('aws_default_region'));
                envWrite('AWS_BUCKET', setting('aws_bucket'));
                envWrite('AWS_URL', $aws_url);
                envWrite('FILESYSTEM_DRIVER', 's3');
            } elseif ($request->default_storage == 'wasabi') {
                $was_url = 'https://'.setting('wasabi_bucket').'.s3.'.setting('wasabi_default_region').'.wasabisys.com';

                envWrite('WAS_ACCESS_KEY_ID', setting('wasabi_access_key_id'));
                envWrite('WAS_SECRET_ACCESS_KEY', setting('wasabi_secret_access_key'));
                envWrite('WAS_DEFAULT_REGION', setting('wasabi_default_region'));
                envWrite('WAS_BUCKET', setting('wasabi_bucket'));
                envWrite('WAS_URL', $was_url);
                envWrite('FILESYSTEM_DRIVER', 'wasabi');
            } else {
                envWrite('FILESYSTEM_DRIVER', 'local');
            }
        }

        if ($request->has('pusher_app_key')) {
            if (checkEmptyProvider('is_pusher_notification_active')) {
                envWrite('PUSHER_APP_KEY', $request->pusher_app_key);
                envWrite('PUSHER_APP_SECRET', $request->pusher_app_secret);
                envWrite('PUSHER_APP_ID', $request->pusher_app_id);
                envWrite('PUSHER_APP_CLUSTER', $request->pusher_app_cluster);
            }
            if ($request->is_pusher_notification_active == '1') {
                envWrite('BROADCAST_DRIVER', 'pusher');
            } else {
                envWrite('BROADCAST_DRIVER', 'log');
            }
        }

        return true;
    }

    public function statusChange($request): bool
    {
        if (in_array($request['name'], get_yrsetting('setting_by_lang'))) {
            $default_language = setting('default_language');
        } else {
            $default_language = 'en';
        }
        $setting        = Setting::where('title', $request['name'])->where('lang', $default_language)->first();

        if (! $setting) {
            $setting        = new Setting();
            $setting->title = $request['name'];
        }

        $setting->value = $request['value'];
        $setting->lang  = $default_language;

        $setting->save();

        Artisan::call('optimize:clear');

        if (in_array('is_pusher_notification_active', $request)) {
            $setting = Setting::where('title', 'is_pusher_notification_active')->where('lang', $default_language)->first();
            if ($setting->value == 1) {
                envWrite('BROADCAST_DRIVER', 'pusher');
            } else {
                envWrite('BROADCAST_DRIVER', 'null');
            }
        }

        return true;
    }




    public function triggerPusherTestEvent()
    { 
        try {
            $pusherAppId = setting('pusher_app_id');
            $pusherKey = setting('pusher_app_key');
            $pusherSecret = setting('pusher_app_secret');
            $pusherCluster = setting('pusher_app_cluster');
            if (empty($pusherAppId) || empty($pusherKey) || empty($pusherSecret) || empty($pusherCluster)) {
                return response()->json([
                    'error' => __('please_update_credential')
                ]);
            }
            try {
                $pusher = new Pusher($pusherKey, $pusherSecret, $pusherAppId, [
                    'cluster' => $pusherCluster,
                    'useTLS' => true,
                ]);
                $pusher->get('/channels');
            } catch (PusherException $e) {
                return $this->formatResponse(false, __('invalid_pusher_credential'), 'admin.pusher.notification', []);
            }
             $companyName = setting('company_name'); 
             $message = sprintf(__('pusher_working'), $companyName);
            event(new TestPusherEvent($message));
            return $this->formatResponse(true, __('event_has_been_sent_successfully'), 'admin.pusher.notification', []);
        } catch (\Exception $e) {
            Log::error('Error triggering event: '.$e->getMessage());
            return $this->formatResponse(false, $e->getMessage(), 'admin.pusher.notification', []);

        }
    }

    public function checkPusherCredentials()
    {
        try {
            $pusherAppId = setting('pusher_app_id');
            $pusherKey = setting('pusher_app_key');
            $pusherSecret = setting('pusher_app_secret');
            $pusherCluster = setting('pusher_app_cluster');
            if (empty($pusherAppId) || empty($pusherKey) || empty($pusherSecret) || empty($pusherCluster)) {
                return $this->formatResponse(false, __('please_update_credential'), 'admin.pusher.notification', []);
            }
            try {
                $pusher = new Pusher($pusherKey, $pusherSecret, $pusherAppId, [
                    'cluster' => $pusherCluster,
                    'useTLS' => true,
                ]);
                $pusher->get('/channels');
                return $this->formatResponse(true, __('pusher_credentials_are_valid'), 'admin.pusher.notification', []);
            } catch (PusherException $e) {
                return $this->formatResponse(false, $e->getMessage(), 'admin.pusher.notification', []);
                // return $this->formatResponse(false, __('invalid_pusher_credential'), 'admin.pusher.notification', []);
            }
        } catch (\Exception $e) {
            Log::error('Error checking Pusher credentials: '.$e->getMessage());
            return $this->formatResponse(false, $e->getMessage(), 'admin.onesignal.notification', []);
        }
    }


    public function checkOneSignalCredentials()
    {
        $onesignalAppId = setting('onesignal_app_id');
        $onesignalRestApiKey = setting('onesignal_rest_api_key');
        if (empty($onesignalAppId) || empty($onesignalRestApiKey)) {
            return $this->formatResponse(false, __('please_update_onesignal_credential'), 'admin.onesignal.notification', []);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $onesignalRestApiKey,
            ])->get('https://onesignal.com/api/v1/apps/' . $onesignalAppId);
                if ($response->successful()) {
                return $this->formatResponse(true, __('onesignal_credentials_are_valid'), 'admin.onesignal.notification', []);
            } else {
                return $this->formatResponse(false, __('invalid_onesignal_credentials'), 'admin.onesignal.notification', []);
            }
        } catch (\Exception $e) { 
            Log::error('Error checking OneSignal credentials: ' . $e->getMessage());
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, $e->getMessage(), 'admin.onesignal.notification', []);
        }
    }

    public function testOneSignalNotification($request)
    {
        try {
            $onesignalAppId = setting('onesignal_app_id');
            $onesignalRestApiKey = setting('onesignal_rest_api_key');
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $onesignalRestApiKey,
            ])->get('https://onesignal.com/api/v1/apps/' . $onesignalAppId);

            if ($response->successful() !== true) {
                return $this->formatResponse(false, __('invalid_onesignal_credentials'), 'admin.onesignal.notification', []);
            }
            $companyName = setting('company_name'); 
            $message = sprintf(__('onesignal_notification_message'), $companyName);
            $heading = __('onesignal_notification');
            $url = route('admin.dashboard'); // Example URL
               // Check if the user's onesignal_player_id is set
            if (isset(auth()->user()->onesignal_player_id)) {
                $this->pushNotification([
                    'ids' => auth()->user()->onesignal_player_id,
                    'message' => $message,
                    'heading' => $heading,
                    'url' => $url,
                ]);

                return $this->formatResponse(true, __('test_onesignal_notification_sent_successfully'), 'admin.onesignal.notification', []);
            } else {
                return $this->formatResponse(false, __('onesignal_player_id_not_set'), 'admin.onesignal.notification', []);
            }
            
            return $this->formatResponse(true, __('test_onesignal_notification_sent_successfully'), 'admin.onesignal.notification', []);
        } catch (\Exception $e) {
            Log::error('Error sending OneSignal test notification: '.$e->getMessage());
            return $this->formatResponse(false, $e->getMessage(), 'admin.onesignal.notification', []);

        }
    }

    


}
