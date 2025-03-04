<?php
namespace App\Repositories\Client;
use Carbon\Carbon;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use App\Models\Segment;
use App\Models\Campaign;
use App\Models\Template;
use App\Models\Attribute;
use App\Enums\MessageEnum;
use App\Traits\ImageTrait;
use App\Traits\CommonTrait;
use App\Models\ContactsList;
use App\Models\Subscription;
use App\Traits\RepoResponse;
use App\Traits\BotReplyTrait;
use App\Traits\WhatsAppTrait;
use App\Enums\MessageStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendWhatsAppCampaignMessageJob;
use Illuminate\Support\Facades\Auth;

class TeleCampaignRepository
{
    use ImageTrait, RepoResponse, WhatsAppTrait,CommonTrait,BotReplyTrait;

    private $model;

    private $contact;

    private $template;

    private $segment;

    private $contact_list;

    private $country;

    private $message;

    private $attribute;

    public function __construct(
        Campaign $model,
        Contact $contact,
        Template $template,
        Segment $segment,
        ContactsList $contact_list,
        Country $country,
        Message $message,
        Attribute $attribute,
    ) {
        $this->model        = $model;
        $this->contact      = $contact;
        $this->template     = $template;
        $this->segment      = $segment;
        $this->contact_list = $contact_list;
        $this->country      = $country;
        $this->message = $message;
        $this->attribute    = $attribute;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $scheduleTime               = Carbon::now();
            //TimeZone Base
            if ($request->send_scheduled == 1) {
                $clientTimezone  = Auth::user()->client->timezone ?? config('app.timezone'); // Asia/Dhaka
                $systemTimezone  = config('app.timezone'); // Asia/Tokyo
                $scheduleTimeStr = $request->schedule_time; // 2024-04-01 15:5
                // Assuming $schedule_time is in the format 'Y-m-d H:i'
                $scheduleTime    = Carbon::createFromFormat('Y-m-d H:i', $scheduleTimeStr, $clientTimezone)
                    ->setTimezone($systemTimezone);
            } else {
                $scheduleTime = Carbon::now();
            }
       
            $campaignRemaining = auth()->user()->activeSubscription->campaign_remaining;
            $messageRemaining = auth()->user()->activeSubscription->conversation_remaining;
            if ($campaignRemaining <= 0) {
                return $this->formatResponse(false, __('insufficient_campaigns_limit'), 'client.whatsapp.campaigns.index', []);
            }
            // if ($messageRemaining <= 0) {
            //     return $this->formatResponse(false, __('insufficient_conversation_limit'), 'client.whatsapp.campaigns.index', []);
            // } 
            $campaign                = new $this->model;
            $campaign->campaign_name = $request->campaign_name;
            $campaign->client_id     = Auth::user()->client_id;
            $campaign->campaign_type = TypeEnum::TELEGRAM;
            $campaign->url_link      = $request->url_link;
            $campaign->save();
            foreach ($request->contact_id as $contactId) {
                $message                    = new $this->message();
                $message->contact_id        = $contactId;
                $message->client_id         = Auth::user()->client_id;
                $message->header_location   = $request->header_location;
                $message->value             = $request->text_message;
                $message->error             = null;
                $message->message_type      = MessageEnum::TEXT;
                $message->source            = TypeEnum::TELEGRAM;
                $message->status            = MessageStatusEnum::SCHEDULED;  
                $message->schedule_at       = $scheduleTime;
                $message->campaign_id       = $campaign->id;
                $message->is_campaign_msg   = 1;
                $message->save();
                $campaign_remaining              = $campaignRemaining - 1;
                // Increment the total_contact attribute of the corresponding campaign by 1
                $campaign->total_contact += 1;
                $campaign->save();
            }
            Subscription::where('client_id', auth()->user()->client_id)->where('status',1)->update(['campaign_remaining' => $campaign_remaining]);
            $this->conversationUpdate(auth()->user()->client_id, $request->contact_id);
            DB::commit();
            return $this->formatResponse(true, __('created_successfully'), 'client.telegram.campaigns.index', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::info('Telegram Campaign', [$e->getMessage()]);
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.telegram.campaigns.index', []);
        }
    }

    public function find($id)
    {
        return Campaign::find($id);
    }

    public function sendScheduleMessage($request)
    {
        $messages = $this->message
            ->where('status', MessageStatusEnum::SCHEDULED)
            ->where('schedule_at', '<', now())
            ->take(100)
            ->get();
        foreach ($messages as $message) {
            SendWhatsAppCampaignMessageJob::dispatch($message);
        }

        return true;
    }

    public function statusUpdate($request,$id)
    {
        try {
            $campaign = $this->model->find($id);
            $campaign->status = $request->status;
            $campaign->status = $request->status;
            $campaign->save();
            return $this->formatResponse(true, __('updated_successfully'), 'client.telegram.campaigns.index', $campaign);
        } catch (\Throwable $e) {
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.telegram.campaigns.index', []);
        }
    }
}
