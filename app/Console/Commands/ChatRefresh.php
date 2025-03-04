<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;

class ChatRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'chat:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        event(new \App\Events\ReceiveUpcomingMessage(Client::find(1)));

        return Command::SUCCESS;
    }
}
