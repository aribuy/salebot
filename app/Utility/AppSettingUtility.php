<?php

namespace App\Utility;

use App\Models\Addon;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AppSettingUtility
{
    public static function currencies()
    {
        return Cache::rememberForever('currencies', function () {
            return Currency::all();
        });
    }

    public static function addons()
    {
        return Cache::rememberForever('addons', function () {
            return Addon::where('status', 1)->get();
        });
    }

    public static function settings()
    {
        return Cache::rememberForever('settings', function () {
            return Setting::all();
        });
    }
    // public static function smsTemplates()
    // {
    //     return Cache::rememberForever('smsTemplates', function () {
    //         return SmsTemplate::all();
    //     });
    // }
}
