<?php

namespace App\Console\Commands;

use App\Traits\UpdateTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AllClear extends Command
{
    use UpdateTrait;

    protected $signature   = 'all:clear';

    protected $description = 'All Data Cleared';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        /*Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed');*/
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');
        cache()->flush();

        $this->delete_directory(base_path('bootstrap/cache/'), false);
        $this->delete_directory(base_path('storage/app/Imports/'), false);
        $this->delete_directory(base_path('storage/debugbar/'), false);
        $this->delete_directory(base_path('storage/framework/cache/data/'), false);
        $this->delete_directory(base_path('storage/framework/cache/laravel-excel/'), false);
        $this->delete_directory(base_path('storage/framework/views/'), false);
        //        $this->delete_directory(base_path('storage/framework/sessions/'),false);
        $this->delete_directory(base_path('storage/logs/'), false);
        $this->delete_directory(base_path('public/install/installer'), false);
        $this->info('All Data Cleared');
        return 0;
    }
}
