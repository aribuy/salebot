<?php
namespace App\Repositories\Client;
use App\Models\Client;
use App\Models\OneSignalToken;
use App\Models\Subscription;
use App\Models\SubscriptionTransactionLog;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\PlanRepository;
use App\Traits\SendMailTrait;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubscriptionRepository
{
    use SendMailTrait;
    use SendNotification;

    protected $emailTemplate;

    protected $planRepository;

    public function __construct(PlanRepository $planRepository, EmailTemplateRepository $emailTemplate)
    {
        $this->planRepository = $planRepository;
        $this->emailTemplate  = $emailTemplate;
    }

    //client
    public function create($plan, $trx_id, $payment_details, $billingInfo, $offline = false, $payment_method = 'stripe')
    {
        $status       = 1;
        if ($offline) {
            $payment_method  = 'offline';
            $payment_details = json_encode(['payment_type' => 'offline']);
            if (isDemoMode()) {
                $status = 1;
            } else {
                $status = 0;
            }
        }
        $client       = Auth::user()->client;
        $is_recurring = 0;
        $expire_date  = now();

        if ($plan->billing_period == 'daily') {
            $expire_date  = now()->addDay(7);
            $is_recurring = 1;
        }if ($plan->billing_period == 'weekly') {
            $expire_date  = now()->addDay(7);
            $is_recurring = 1;
        } elseif ($plan->billing_period == 'monthly') {
            $expire_date  = now()->addMonths();
            $is_recurring = 1;
        } elseif ($plan->billing_period == 'quarterly') {
            $expire_date  = now()->addMonths(3);
            $is_recurring = 1;
        } elseif ($plan->billing_period == 'half_yearly') {
            $expire_date  = now()->addMonths(6);
            $is_recurring = 1;
        } elseif ($plan->billing_period == 'yearly') {
            $expire_date  = now()->addYears();
            $is_recurring = 1;
        }
        $subscription = Subscription::where('client_id', $client->id)->where('status', 1)->first();
        if ($subscription) {
            $subscription->status = 3;
            $subscription->save();
        }

        $this->sendAdminNotifications([
            'message' => __('company_subscribed_to_plan', ['company' => $client->company_name, 'plan' => $plan->name]),
            'heading' => $client->name,
            'url'     => route('packages.subscribe-list'),
        ]);

        if ($offline) {
            $this->sendAdminNotifications([
                'message' => __('offline_payment_waiting_for_approval'),
                'heading' => $client->name,
                'url'     => route('packages.subscribe-list'),
            ]);
        }
        $data         = [
            'client_id'              => $client->id,
            'plan_id'                => $plan->id,
            'is_recurring'           => $is_recurring,
            'status'                 => $status,
            'purchase_date'          => now(),
            'expire_date'            => $expire_date,
            'price'                  => $plan->price,
            'package_type'           => $plan->billing_period,
            'contact_limit'          => $plan->contact_limit,
            'campaign_limit'         => $plan->campaigns_limit,
            'campaign_remaining'     => $plan->campaigns_limit,
            'conversation_limit'     => $plan->conversation_limit,
            'conversation_remaining' => $plan->conversation_limit,
            'team_limit'             => $plan->team_limit,
            'telegram_access'        => (bool) $plan->telegram_access,
            'trx_id'                 => $trx_id,
            'payment_method'         => $payment_method,
            'payment_details'        => $payment_details,
            'client'                 => Client::find($client->id),
            'billing_name'           => $billingInfo['billing_name'],
            'billing_email'          => $billingInfo['billing_email'],
            'billing_address'        => $billingInfo['billing_address'],
            'billing_city'           => $billingInfo['billing_city'],
            'billing_state'          => $billingInfo['billing_state'],
            'billing_zip_code'       => $billingInfo['billing_zipcode'],
            'billing_country'        => $billingInfo['billing_country'],
            'billing_phone'          => $billingInfo['billing_phone'],
            'subject'                => __('package_subscription_confirmation'),
        ];
        if (isMailSetupValid()) {
            $this->sendmail($client->user->email, 'emails.purchase_mail', $data);
        }
        session()->forget('billing_info');

        $log          = SubscriptionTransactionLog::create(['description' => __('you purchased ').$plan->name.__('package_successfully'),
            'client_id'                                                   => $client->id]);

        return Subscription::create($data);
    }

    //admin
    public function store($request, $plan, $trx_id, $payment_details, $offline = false, $payment_method = 'stripe')
    {
        $status       = 1;
        if ($offline) {
            $payment_method  = 'offline';
            $payment_details = json_encode(['payment_type' => 'offline']);
            $status          = 1;
        }
        $client       = Client::where('id', $request->client_id)->first();
        $is_recurring = 0;
        $expire_date  = now();

        if ($plan->billing_period == 'daily') {
            $expire_date  = now()->addDay(7);
            $is_recurring = 0;
        }if ($plan->billing_period == 'weekly') {
            $expire_date  = now()->addDay(7);
            $is_recurring = 0;
        } elseif ($plan->billing_period == 'monthly') {
            $expire_date  = now()->addMonths();
            $is_recurring = 0;
        } elseif ($plan->billing_period == 'quarterly') {
            $expire_date  = now()->addMonths(3);
            $is_recurring = 0;
        } elseif ($plan->billing_period == 'half_yearly') {
            $expire_date  = now()->addMonths(6);
            $is_recurring = 0;
        } elseif ($plan->billing_period == 'yearly') {
            $expire_date  = now()->addYears();
            $is_recurring = 0;
        }
        $subscription = Subscription::where('client_id', $client->id)->where('status', 1)->first();
        if ($subscription) {
            $subscription->status = 2;
            $subscription->save();
        }

        $data         = [
            'client_id'              => $client->id,
            'plan_id'                => $plan->id,
            'is_recurring'           => $is_recurring,
            'status'                 => $status,
            'purchase_date'          => now(),
            'expire_date'            => $expire_date,
            'price'                  => $request->amount,
            'package_type'           => $plan->billing_period,
            'contact_limit'          => $plan->contact_limit,
            'campaign_limit'         => $plan->campaigns_limit,
            'campaign_remaining'     => $plan->campaigns_limit,
            'conversation_limit'     => $plan->conversation_limit,
            'conversation_remaining' => $plan->conversation_limit,
            'team_limit'             => $plan->team_limit,
            'telegram_access'        => (bool) $plan->telegram_access,
            'trx_id'                 => $trx_id,
            'payment_method'         => $payment_method,
            'payment_details'        => $payment_details,
            'client'                 => Client::find($client->id),
        ];
        $log          = SubscriptionTransactionLog::create(['description' => 'Admin has purchased '.$plan->name.' package for you',
            'client_id'                                                   => $client->id]);

        return Subscription::create($data);
    }

    public function subscribeListStatus($request, $id)
    {
        $subscribe         = Subscription::findOrfail($id);
        $subscribe->status = $request['status'];
        if ($request['status'] == 2) {
            $payment_method         = $subscribe->payment_method;
            if ($payment_method == 'stripe') {
                $this->cancelStripe($subscribe);
            } elseif ($payment_method == 'paddle') {
                $this->cancelPaddle($subscribe);
            } elseif ($payment_method == 'paypal') {
                $this->cancelPaypal($subscribe);
            }
            $subscribe->canceled_at = now();
        }
        $status            = __('pending');
        if ($request['status'] == 1) {
            $status = __('active');
        } elseif ($request['status'] == 2) {
            $status = __('cancelled');
        } elseif ($request['status'] == 3) {
            $status = __('rejected');
        }
        $msg               = __('subscription_status_updated', ['status' => $status]);
        $this->pushNotification([
            'ids'     => OneSignalToken::where('client_id', $subscribe->client_id)->pluck('subscription_id')->toArray(),
            'message' => $msg,
            'heading' => __('status_has_been_updated'),
            'url'     => route('client.dashboard'),
        ]);
        $this->sendNotification([$subscribe->client->user->id], $msg, 'success', route('client.dashboard'));

        $log               = SubscriptionTransactionLog::create(['description' => 'Admin '.$status.' your plan',
            'client_id'                                                        => $subscribe->client_id]);

        return $subscribe->save();
    }

    public function updateSubscriptionLimits($subscriptionId, $newLimits)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $subscription->contact_limit          += intval($newLimits['new_contacts_limit']);
        $subscription->campaign_remaining     += intval($newLimits['new_campaigns_limit']);
        $subscription->campaign_limit         += intval($newLimits['new_campaigns_limit']);
        $subscription->conversation_remaining += intval($newLimits['new_conversation_limit']);
        $subscription->conversation_limit     += intval($newLimits['new_conversation_limit']);
        $subscription->team_limit             += intval($newLimits['new_team_limit']);

        $log          = SubscriptionTransactionLog::create(['description' => 'Admin update some credit in your Subscription',
            'client_id'                                                   => $subscription->client_id]);
        $subscription->save();

        return $subscription;
    }

    public function cancelSubscription($id)
    {
        $subscription              = Subscription::find($id);

        $payment_method            = $subscription->payment_method;

        if ($payment_method == 'stripe') {
            $this->cancelStripe($subscription);
        } elseif ($payment_method == 'paddle') {
            $this->cancelPaddle($subscription);
        } elseif ($payment_method == 'paypal') {
            $this->cancelPaypal($subscription);
        }

        $subscription->canceled_at = now();
        $subscription->status      = 2;
        $log                       = SubscriptionTransactionLog::create(['description' => 'You cancel Your Subscription',
            'client_id'                                                                => auth()->user()->client_id]);
        $subscription->save();

        return $subscription;
    }

    public function stopRecurring($id)
    {
        $subscription               = Subscription::find($id);

        $payment_method             = $subscription->payment_method;

        if ($payment_method == 'stripe') {
            $this->cancelStripe($subscription);
        } elseif ($payment_method == 'paddle') {
            $this->cancelPaddle($subscription);
        } elseif ($payment_method == 'paypal') {
            $this->cancelPaypal($subscription);
        }
        $cancel_date                = Carbon::parse($subscription->purchase_date);
        if ($subscription->package_type == 'daily') {
            $cancel_date = $cancel_date->addDay();
        } elseif ($subscription->package_type == 'weekly') {
            $cancel_date = $cancel_date->addWeek();
        } elseif ($subscription->package_type == 'monthly') {
            $cancel_date = $cancel_date->addMonth();
        } elseif ($subscription->package_type == 'quarterly') {
            $cancel_date = $cancel_date->addMonths(3);
        } elseif ($subscription->package_type == 'half_yearly') {
            $cancel_date = $cancel_date->addMonths(6);
        } elseif ($subscription->package_type == 'yearly') {
            $cancel_date = $cancel_date->addYear();
        }
        $subscription->canceled_at  = $cancel_date;
        $subscription->is_recurring = 0;

        if (auth()->user()->user_type == 'admin') {
            $log = SubscriptionTransactionLog::create(['description' => 'admin stop your recurring',
                'client_id'                                          => $subscription->client_id]);
        } else {
            $log = SubscriptionTransactionLog::create(['description' => 'you stop your recurring',
                'client_id'                                          => $subscription->client_id]);
        }

        $subscription->save();

        return $subscription;
    }

    public function enableRecurring($id)
    {
        $subscription               = Subscription::find($id);

        $payment_method             = $subscription->payment_method;

        if ($payment_method == 'stripe') {
            $this->cancelStripe($subscription);
        } elseif ($payment_method == 'paddle') {
            $this->cancelPaddle($subscription);
        } elseif ($payment_method == 'paypal') {
            $this->cancelPaypal($subscription);
        }
        $cancel_date                = Carbon::parse($subscription->purchase_date);
        if ($subscription->package_type == 'daily') {
            $cancel_date = $cancel_date->addDay();
        } elseif ($subscription->package_type == 'weekly') {
            $cancel_date = $cancel_date->addWeek();
        } elseif ($subscription->package_type == 'monthly') {
            $cancel_date = $cancel_date->addMonth();
        } elseif ($subscription->package_type == 'quarterly') {
            $cancel_date = $cancel_date->addMonths(3);
        } elseif ($subscription->package_type == 'half_yearly') {
            $cancel_date = $cancel_date->addMonths(6);
        } elseif ($subscription->package_type == 'yearly') {
            $cancel_date = $cancel_date->addYear();
        }
        $subscription->canceled_at  = $cancel_date;
        $subscription->is_recurring = 1;
        $log                        = SubscriptionTransactionLog::create(['description' => 'You enable subscription recurring',
            'client_id'                                                                 => $subscription->client_id]);
        $subscription->save();

        return $subscription;
    }

    public function cancelStripe($subscription)
    {
        $stripe_subscript_id = getArrayValue('subscription', $subscription->payment_details);
        $response            = [];
        if ($stripe_subscript_id) {
            $headers  = [
                'Authorization' => 'Basic '.base64_encode(setting('stripe_secret').':'),
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ];

            $data     = [
                'invoice_now' => 'false',
            ];
            $response = httpRequest('https://api.stripe.com/v1/subscriptions/'.$stripe_subscript_id, $data, $headers, true, 'DELETE');
        }

        return $response;
    }

    public function cancelPaddle($subscription)
    {
        $transaction_id  = $subscription->payment_details['transaction_id'];

        $headers         = [
            'Authorization' => 'Bearer '.setting('paddle_api_key'),
        ];
        if (setting('is_paddle_sandbox_mode_activated')) {
            $base_url = 'https://sandbox-api.paddle.com/';

        } else {
            $base_url = 'https://api.paddle.com/';
        }
        $data            = [
            'effective_from' => 'next_billing_period',
        ];
        $response        = httpRequest($base_url."transactions/$transaction_id", $data, $headers, false, 'GET');
        $subscription_id = $response['data']['subscription_id'];

        return httpRequest($base_url."subscriptions/$subscription_id/cancel", $data, $headers);
    }

    public function paypalTokenGenerator($base_url): string
    {
        //generate access token
        $headers  = [
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic '.base64_encode(setting('paypal_client_id').':'.setting('paypal_client_secret')),
        ];
        $data     = [
            'grant_type' => 'client_credentials',
        ];
        $response = httpRequest($base_url.'/v1/oauth2/token', $data, $headers, true);

        return $response['token_type'].' '.$response['access_token'];
    }

    public function cancelPaypal($subscription)
    {
        if (setting('is_paypal_sandbox_mode_activated')) {
            $base_url = 'https://api-m.sandbox.paypal.com';

        } else {
            $base_url = 'https://api-m.paypal.com';
        }
        $paypal_subscription_id = $subscription->payment_details['id'];
        $headers                = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->paypalTokenGenerator($base_url),
        ];

        $data                   = [
            'reason' => 'stopped by admin',
        ];

        return httpRequest($base_url.'/v1/billing/subscriptions/'.$paypal_subscription_id.'/cancel', $data, $headers);
    }

    public function updateValidity($data, $id)
    {
        $subscription                  = Subscription::find($id);

        $date                          = '';
        if ($data['interval'] == 'day') {
            $date = Carbon::parse($subscription->expire_date)->addDays($data['time']);
        } elseif ($data['interval'] == 'week') {
            $date = Carbon::parse($subscription->expire_date)->addWeeks($data['time']);
        } elseif ($data['interval'] == 'month') {
            $date = Carbon::parse($subscription->expire_date)->addMonths($data['time']);
        } elseif ($data['interval'] == 'year') {
            $date = Carbon::parse($subscription->expire_date)->addYears($data['time']);
        }

        $payment_details               = $subscription->payment_details;
        $payment_method                = $subscription->payment_method;
        if ($payment_method == 'stripe') {
            $payment_details = $this->updateStripe($subscription, $date->timestamp);
        } elseif ($payment_method == 'paddle') {
            $payment_details = $this->updatePaddle($subscription);
        } elseif ($payment_method == 'paypal') {
            $payment_details = $this->updatePaypal($subscription);
        }
        $subscription->payment_details = $payment_details;
        $subscription->expire_date     = $date;
        $subscription->save();

        return $subscription;
    }

    public function updateStripe($subscription, $date)
    {
        $this->cancelStripe($subscription);

        $headers = [
            'Authorization' => 'Basic '.base64_encode(setting('stripe_secret').':'),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];
        $url     = 'https://api.stripe.com/v1/subscriptions';

        $fields  = [
            'customer'             => $subscription->payment_details['customer'],
            'currency'             => 'USD',
            'items'                => [
                [
                    'price'    => $this->planRepository->getPGCredential($subscription->plan_id, 'stripe'),
                    'quantity' => 1,
                ],
            ],
            'billing_cycle_anchor' => $date,
        ];

        return httpRequest($url, $fields, $headers, true);
    }
}
