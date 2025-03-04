<?php

namespace App\Http\Controllers\Client;

use App\Enums\MessageStatusEnum;
use App\Enums\TypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Timezone;
use App\Models\User;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\UserRepository;
use App\Services\NewContactsService;
use App\Services\TelegramCampaignService;
use App\Services\TelegramMessageService;
use App\Services\TotalContactsService;
use App\Services\WhatsAppCampaignService;
use App\Services\WhatsAppMessageService;
use App\Traits\SendMailTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientDashboardController extends Controller
{
    use SendMailTrait;

    protected $emailTemplate;

    public function __construct(EmailTemplateRepository $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    public function index(Request $request)
    {
        $client             = auth()->user()->client;
        $activeSubscription = auth()->user()->activeSubscription;
        $total_team         = User::where('client_id', $client->user->client_id)->where('status', 1)->count();
        $total_contacts     = Contact::where('client_id', $client->user->client_id)->active()->count();
        $delivered_message  = Message::where('client_id', $client->user->client_id)->where('source', TypeEnum::WHATSAPP)->whereNotNull('campaign_id')->whereIn('status', [MessageStatusEnum::DELIVERED, MessageStatusEnum::READ])->count();
        $read_message       = Message::where('client_id', $client->user->client_id)->where('source', TypeEnum::WHATSAPP)->whereNotNull('campaign_id')->where('status', MessageStatusEnum::READ)->count();
        $subscription       = Auth::user()->client->activeSubscription;
        $data               = app(TotalContactsService::class)->execute($request);
        if (isDemoMode()) {
            $data = [
                'client'              => $client,
                'active_subscription' => auth()->user()->activeSubscription,
                'charts'              => [
                    'labels'                => $data['labels'],
                    'total_contacts'        => [52, 100, 115, 118, 187, 275, 334, 444, 544, 555, 678, 787],
                    'new_contacts'          => [52, 48, 15, 18, 87, 175, 234, 344, 444, 455, 578, 687],
                    'whatsapp_campaign'     => [5, 10, 5, 3, 5, 0, 8, 10, 5, 3, 5, 2],
                    'telegram_campaign'     => [2, 4, 2, 3, 5, 5, 8, 10, 5, 3, 5, 8],
                    'whatsapp_conversation' => [750, 785, 15, 18, 87, 175, 234, 344, 444, 455, 578, 687],
                    'telegram_conversation' => [650, 485, 25, 88, 8, 15, 334, 344, 424, 355, 578, 587],
                ],
                'usages'              => [
                    'team'         => 1,
                    'campaign'     => 5,
                    'contact'      => 524,
                    'conversation' => 3658,
                ],
                'read_rate'           => 98.25,
            ];
        } else {
            $data = [
                'client'              => $client,
                'active_subscription' => $activeSubscription,
                'charts'              => [
                    'labels'                => $data['labels'],
                    'total_contacts'        => $data['data'],
                    'new_contacts'          => app(NewContactsService::class)->execute($request),
                    'whatsapp_campaign'     => app(WhatsAppCampaignService::class)->execute($request),
                    'telegram_campaign'     => app(TelegramCampaignService::class)->execute($request),
                    'whatsapp_conversation' => app(WhatsAppMessageService::class)->execute($request),
                    'telegram_conversation' => app(TelegramMessageService::class)->execute($request),
                ],
                'usages'              => [
                    'team'         => $total_team,
                    'campaign'     => $activeSubscription->campaign_limit                                                         - $activeSubscription->campaign_remaining,
                    'contact'      => $total_contacts,
                    'conversation' => ($activeSubscription->conversation_remaining > 0) ? $activeSubscription->conversation_limit - $activeSubscription->conversation_remaining : 0,
                ],
                'read_rate'           => ($read_message != 0) ? $read_message / $delivered_message * 100 : 0,
            ];
        }

        return view('backend.client.dashboard', $data);
    }

    public function profile(UserRepository $userRepository)
    {
        $user_id = auth()->user()->id;
        $data    = [
            'time_zones' => Timezone::all(),
            'user'       => $userRepository->find($user_id),
        ];

        return view('backend.client.auth.profile', $data);
    }

    public function chat(UserRepository $userRepository)
    {
        $user_id = auth()->user()->id;
        $user    = $userRepository->find($user_id);

        return view('backend.client.chat.index');
    }

    public function profileUpdate(Request $request, UserRepository $userRepository): \Illuminate\Http\JsonResponse
    {

        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $id = auth()->user()->id;
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.Request()->id,
            'phone'      => 'required|unique:users,phone,'.Request()->id,

        ]);

        try {
            $userRepository->update($request->all(), auth()->user()->id);
            Toastr::success(__('update_successful'));

            return response()->json([
                'success' => __('update_successful'),
            ]);
        } catch (Exception $e) {
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function passwordChange()
    {
        return view('backend.client.auth.password_change');
    }

    public function passwordUpdate(Request $request, UserRepository $userRepository)
    {

        $request->validate([
            'current_password' => ['required'],
            'password'         => 'required|min:6|max:32|confirmed',
        ]);
        $user = $userRepository->findByEmail(auth()->user()->email);

        if (Hash::check($request->current_password, $user->password)) {
            try {
                $user->password = bcrypt($request->password);
                $user->save();
                Toastr::success(__('successfully_password_changed'));

                return response()->json([
                    'success' => __('successfully_password_changed'),
                    'route'   => route('client.profile.password-change'),
                ]);
            } catch (Exception $e) {
                Toastr::warning(__($e));

                return response()->json(['error' => __('something_went_wrong_please_try_again')]);
            }
        } else {
            Toastr::warning(__('sorry_old_password_not_match'));

            return response()->json(['error' => 'sorry_old_password_not_match']);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
