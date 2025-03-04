<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelSubscriptions extends Command
{
    protected $signature   = 'cancel:subscription';

    protected $description = 'Command description';

    public function handle()
    {
        \App\Models\Subscription::whereDate('canceled_at', now())->where('is_recurring', 0)->update([
            'status' => 2,
        ]);

        return Command::SUCCESS;
    }
}
