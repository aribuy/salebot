<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
class LogController extends Controller
{
    public function showLog()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
        } else {
            $logContent = 'Log file does not exist.';
        }
        return view('backend.admin.logs', compact('logContent'));
    }
}
