<?php

namespace App\Repositories\Webhook;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Traits\BotReplyTrait;
use App\Traits\TelegramTrait;
use App\Traits\WhatsAppTrait;
use App\Services\WhatsAppService;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

class TelegramRepository
{
    const TELEGRAM_BASE_URL = 'https://api.telegram.org/';
    use TelegramTrait, WhatsAppTrait, BotReplyTrait;
    private $clientModel;
    private $country;
    private $contact;
    private $message;
    protected $whatsappService;
    public function __construct(
        Client $clientModel,
        Country $country,
        Contact $contact,
        Message $message,
        WhatsAppService $whatsappService
    ) {
        $this->clientModel     = $clientModel;
        $this->contact         = $contact;
        $this->message    = $message;
        $this->whatsappService = $whatsappService;
        $this->country         = $country;
    }

    public function receiveResponse(Request $request, $token)
    {
        $clientSetting = $this->getClientSettingByToken($token);
        if (empty($clientSetting) || empty($clientSetting->client)) {
            return response()->json(['status' => 'success']);
        }
        config(['telegram.bots.mybot.token' => $clientSetting->access_token]);
        try {
            $updates = Telegram::commandsHandler(true);
            $this->handleNewGroupInfo($updates, $clientSetting);
            $this->processGroupSubscribers($updates, $clientSetting);
            // Update bot administrator status in Telegram group
            $this->updateBotAdminStatus($updates, $clientSetting);
            // Remove bot or subscriber
            $this->removeBotOrSubscriber($updates, $clientSetting);
            $this->processMessage($updates, $clientSetting);
            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            \Log::error('Error in receiveResponse: ' . $e->getMessage());
            return response()->json(['send' => false, 'error' => __('an_unexpected_error_occurred_please_try_again_later.'), 'data' => $request]);
        }
    }

}
