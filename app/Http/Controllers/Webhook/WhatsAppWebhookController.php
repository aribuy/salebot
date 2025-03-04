<?php
namespace App\Http\Controllers\Webhook;
use Illuminate\Http\Request;
use App\Traits\WhatsAppTrait;
use App\Http\Controllers\Controller;
use App\Repositories\Webhook\WhatsappRepository;
use App\Repositories\Client\WaCampaignRepository;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    use WhatsAppTrait;
    protected $campaign;
    protected $whatsappRepo;

    public function __construct(WaCampaignRepository $campaign, WhatsappRepository $whatsappRepo)
    {
        $this->campaign = $campaign;
        $this->whatsappRepo = $whatsappRepo;
    }
 

    public function verifyToken(Request $request,$token)
    {
        return $this->whatsappRepo->verifyToken($request,$token);
    }

    public function receiveResponse(Request $request,$token)
    {
        return  $this->whatsappRepo->receiveResponse($request,$token);
    }

    public function sendScheduleMessage(Request $request)
    {
        return  $this->campaign->sendScheduleMessage($request);
    }


 
}
