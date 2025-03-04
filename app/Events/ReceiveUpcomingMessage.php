<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReceiveUpcomingMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function broadcastOn()
    {
        $client_id = $this->client->id;

        return ["message-received-$client_id"];
    }
}
