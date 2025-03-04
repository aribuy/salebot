<?php
namespace App\Http\Controllers\Webhook;
use Illuminate\Http\Request;
use App\Traits\WhatsAppTrait;
use App\Http\Controllers\Controller;
use App\Repositories\Webhook\TelegramRepository;

class TelegramController extends Controller
{
    use WhatsAppTrait;
    protected $campaign;
    protected $whatsappRepo;

    public function __construct(TelegramRepository $whatsappRepo)
    {
        $this->whatsappRepo = $whatsappRepo;
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
