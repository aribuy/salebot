<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\StripeSession;
use App\Models\SubscriptionTransactionLog;
use App\Models\User;
use App\Repositories\Client\SubscriptionRepository;
use App\Repositories\PlanRepository;
use App\Traits\PaymentTrait;
use App\Traits\RepoResponse;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    use PaymentTrait, RepoResponse;

    protected $planRepository;

    protected $subscriptionRepository;

    public function __construct(PlanRepository $planRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->planRepository         = $planRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function mySubscription()
    {
        $Subscription       = auth()->user()->activeSubscription;
        $log_details        = SubscriptionTransactionLog::where('client_id', auth()->user()->client_id)->latest()->paginate(10);
        $total_team         = User::where('client_id', auth()->user()->client_id)->where('status', 1)->count();
        $total_contacts     = Contact::where('client_id', auth()->user()->client_id)->where('status', 1)->count();
        $teams_remaining    = $Subscription->team_limit    - $total_team;
        $contacts_remaining = $Subscription->contact_limit - $total_contacts;
        $client             = auth()->user()->client;
        $data               = [
            'client'              => $client,
            'team_remaining'      => $teams_remaining,
            'contact_remaining'   => $contacts_remaining,
            'active_subscription' => auth()->user()->activeSubscription,
            'log_detail'          => $log_details,
        ];

        return view('backend.client.subscription.my_subscription', $data);
    }

    public function pendingSubscription()
    {

        return view('backend.client.subscription.pending_subscription');
    }

    public function availablePlans()
    {

        try {
            $client = auth()->user()->client;
            $data   = [
                'packages'            => $this->planRepository->activePlans(),
                'client'              => $client,
                'active_subscription' => $client->activeSubscription,
            ];

            return view('backend.client.subscription.upgrade_plan', $data);
        } catch (\Exception $e) {

            return back()->with('error', 'something_went_wrong_please_try_again');
        }
    }

    public function upgradePlan($id): Factory|View|Application|RedirectResponse
    {
        try {

            $plan = $this->planRepository->find($id);
            $data = [
                'package' => $plan,
                'trx_id'  => Str::random(),
            ];

            return view('backend.client.subscription.payment_page', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'something_went_wrong_please_try_again');
        }
    }

    public function offlineClaim(Request $request)
    {
        try {
            $trx_id = 'offline-' . uniqid();
            $plan   = $this->planRepository->find($request->plan_id);
            $this->subscriptionRepository->create($plan, $trx_id, '', $request, true);

            return $this->formatResponse(true, __('purchased_successfully'), route('client.pending.subscription'), []);
        } catch (\Exception $e) {
            return $this->formatResponse(false, $e->getMessage(), 'client.offline.claim', []);
        }
    }

    public function createStripeCustomer($client)
    {
        $data     = [
            'name'     => $client->name,
            'email'    => $client->email,
            'metadata' => $client,
        ];
        $headers  = [
            'Authorization' => 'Basic ' . base64_encode(setting('stripe_secret') . ':'),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];
        $response = httpRequest('https://api.stripe.com/v1/customers', $data, $headers, true);

        return $client->update(['stripe_customer_id' => $response['id']]);
    }

    public function upgradeFreePlan(Request $request)
    {
        $package = $this->planRepository->findActive($request->package_id);
        if ($package->is_free == 1 && $package->price == 0) {
            $trx_id = 'free-' . uniqid();
            $this->subscriptionRepository->create($package, $trx_id, '', $request, false, 'free');

            return redirect()->route('client.dashboard');
        }

        return back();
    }

    private function billingData($request): array
    {
        $billingInfo = [
            'billing_name'          => $request->billing_name,
            'billing_email'         => $request->billing_email,
            'billing_address'       => $request->billing_address,
            'billing_city'          => $request->billing_city,
            'billing_state'         => $request->billing_state,
            'billing_zipcode'       => $request->billing_zipcode,
            'billing_country'       => $request->billing_country,
            'country_selector_code' => $request->country_selector_code,
            'billing_phone'         => $request->billing_phone,
            'full_number'           => $request->full_number,
            'plan_id'               => $request->plan_id,
            'trx_id'                => $request->trx_id,
        ];
        Session::put('billing_info', $billingInfo);

        return $billingInfo;
    }

    public function stripeRedirect(Request $request): RedirectResponse
    {
        try {
            $package        = $this->planRepository->find($request->package_id);

            $client         = auth()->user()->client;
            if (!$client->stripe_customer_id) {
                $this->createStripeCustomer($client);
            }
            $plan_id        = $this->planRepository->getPGCredential($request->package_id, 'stripe');
            if (!$plan_id) {
                Toastr::error('stripe_plan_not_found');

                return redirect()->route('client.available.plans');
            }

            $stripe_session = StripeSession::create([
                'plan_id'   => $package->id,
                'client_id' => $client->id,
            ]);

            $this->billingData($request);

            $session        = [
                'customer'             => $client->stripe_customer_id, // ID of the Stripe customer
                'payment_method_types' => ['card'], // Payment method types accepted
                'line_items'           => [
                    [
                        'price'    => $plan_id, // ID of the Stripe price
                        'quantity' => 1,
                    ],
                ],
                'mode'                 => 'subscription', // SubscriptionMiddleWare mode
                'success_url'          => route('client.stripe.payment.success', ['session_id' => $stripe_session->id, 'trx_id' => $request->trx_id]),
                'cancel_url'           => url()->previous(),
            ];
            $headers        = [
                'Authorization' => 'Basic ' . base64_encode(setting('stripe_secret') . ':'),
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ];
            $response       = httpRequest('https://api.stripe.com/v1/checkout/sessions', $session, $headers, true);

            if (isset($response['error']) && isset($response['error']['message'])) {
                Toastr::error($response['error']['message']);

                return redirect()->back();
            }

            $stripe_session->update(['stripe_session_id' => $response['id']]);

            return redirect($response['url']);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error($e->getMessage());

            // Check if the exception is a Stripe API error and the error code is 'resource_missing'
            if ($e instanceof ApiErrorException && $e->getError()->code === 'resource_missing') {
                Toastr::error('Customer not found. Please try again.');

                return redirect()->back(); // Redirect the user back to the previous page
            }

            // Handle other exceptions
            Toastr::error('An error occurred. Please try again later.');

            return redirect()->back(); // Redirect the user back to the previous page
        }
    }

    public function stripeSuccess(Request $request): Redirector|RedirectResponse|Application
    {
        try {
            $session        = StripeSession::find($request->session_id);
            if (!$session) {
                Toastr::error('invalid_request');

                return redirect()->route('client.available.plans');
            }
            $headers        = [
                'Authorization' => 'Basic ' . base64_encode(setting('stripe_secret') . ':'),
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ];
            $stripe_session = httpRequest('https://api.stripe.com/v1/checkout/sessions/' . $session->stripe_session_id, [], $headers, false, 'GET');
            if (!$stripe_session) {
                Toastr::error('invalid_request');

                return redirect()->route('client.available.plans');
            }
            if ($stripe_session['payment_status'] != 'paid') {
                Toastr::error('invalid_request');

                return redirect()->route('client.available.plans');
            }
            $billingInfo    = session('billing_info');
            $package        = $session->plan;
            $this->subscriptionRepository->create($package, $request->trx_id, $stripe_session, $billingInfo);
            Toastr::success('purchased_successfully');

            return redirect()->route('client.dashboard');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            if (config('app.debug')) {
                dd($e->getMessage());
            }

            return redirect()->route('client.available.plans');
        }
    }

    public function paypalTokenGenerator($base_url): string
    {
        //generate access token
        $headers  = [
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode(setting('paypal_client_id') . ':' . setting('paypal_client_secret')),
        ];
        $data     = [
            'grant_type' => 'client_credentials',
        ];
        $response = httpRequest($base_url . '/v1/oauth2/token', $data, $headers, true);

        return $response['token_type'] . ' ' . $response['access_token'];
    }

    public function paypalRedirect(Request $request): Redirector|RedirectResponse|Application
    {
        if (setting('is_paypal_sandbox_mode_activated')) {
            $base_url = 'https://api-m.sandbox.paypal.com';
        } else {
            $base_url = 'https://api-m.paypal.com';
        }
        $plan_id           = $this->planRepository->getPGCredential($request->package_id, 'paypal');

        if (!$plan_id) {
            Toastr::error('paypal_plan_not_found');

            return redirect()->route('client.available.plans');
        }

        $headers           = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->paypalTokenGenerator($base_url),
        ];
        $this->billingData($request);
        

        $subscription_data = [
            'plan_id'             => $plan_id,
            'custom_id'           => $request->package_id,
            'application_context' => [
                'brand_name'          => setting('system_name'),
                'locale'              => 'en-US',
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                'user_action'         => 'SUBSCRIBE_NOW',
                'payment_method'      => [
                    'payer_selected'  => 'PAYPAL',
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                ],
                'return_url'          => route('client.paypal.payment.success', ['trx_id' => $request->trx_id]),
                'cancel_url'          => url()->previous(),
            ],
        ];

        $response          = httpRequest($base_url . '/v1/billing/subscriptions', $subscription_data, $headers);

        return redirect($response['links'][0]['href']);
    }

    public function paypalSuccess(Request $request): RedirectResponse
    {
        try {
            if (setting('is_paypal_sandbox_mode_activated')) {
                $base_url = 'https://api-m.sandbox.paypal.com';
            } else {
                $base_url = 'https://api-m.paypal.com';
            }
            $headers     = [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->paypalTokenGenerator($base_url),
            ];
            $response    = httpRequest($base_url . '/v1/billing/subscriptions/' . $request->subscription_id, [], $headers, false, 'GET');
            $package     = $this->planRepository->find(getArrayValue('custom_id', $response));
            if (!$package) {
                Toastr::error('invalid_request');

                return redirect()->route('client.available.plans');
            }
            $billingInfo = session('billing_info');
            $this->subscriptionRepository->create($billingInfo, $package, $request->trx_id, $response, false, 'paypal');
            Toastr::success('purchased_successfully');

            return redirect()->route('client.dashboard');
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            if (config('app.debug')) {
                dd($e->getMessage());
            }

            return redirect()->route('client.available.plans');
        }
    }

    public function paddleRedirect(Request $request): View|Factory|Application|RedirectResponse
    {
        try {
            $this->billingData($request);
            $data = [
                'plan'     => $this->planRepository->find($request->package_id),
                'price_id' => $this->planRepository->getPGCredential($request->package_id, 'paddle'),
                'trx_id'   => $request->trx_id,
                'client'   => auth()->user()->client,
            ];

            return view('backend.client.subscription.paddle', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return redirect()->route('client.available.plans');
        }
    }

    public function paddleSuccess(Request $request): JsonResponse
    {
        try {
            $payment_details                = $request->data;
            $package                        = $this->planRepository->find($request->plan_id);
            if (getArrayValue('status', $payment_details) != 'completed') {
                Toastr::error('invalid_request');

                return response()->json([
                    'error' => 'invalid_request',
                    'route' => route('client.available.plans'),
                ]);
            }
            $client                         = auth()->user()->client;

            if (getArrayValue('id', $payment_details['customer']) && !$client->paddle_customer_id) {
                $client->update(['paddle_customer_id' => getArrayValue('id', $payment_details['customer'])]);
            }

            $payment_data['id']             = getArrayValue('id', $payment_details);
            $payment_data['transaction_id'] = getArrayValue('transaction_id', $payment_details);
            $billingInfo                    = session('billing_info');
            $this->subscriptionRepository->create($billingInfo, $package, $request->trx_id, $payment_data, false, 'paddle');
            Toastr::success('purchased_successfully');

            return response()->json([
                'error' => 'invalid_request',
                'route' => route('client.dashboard'),
            ]);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return response()->json([
                'error' => 'invalid_request',
                'route' => route('client.available.plans'),
            ]);
        }
    }

    public function razorPayRedirect(Request $request): JsonResponse
    {
        try {
            $image            = 'https://livedemo.salebot.app/public/images/20240318161457-light_logo243.png';
            if (setting('dark_logo') && @is_file_exists(setting('dark_logo')['original_image'])) {
                $image = get_media(setting('dark_logo')['original_image']);
            } elseif (setting('light_logo') && @is_file_exists(setting('light_logo')['original_image'])) {
                $image = get_media(setting('light_logo')['original_image']);
            }
            $this->billingData($request);
            $plan             = $this->planRepository->find($request->plan_id);

            $subscriptionData = [
                'plan_id'         => $this->planRepository->getPGCredential($request->plan_id, 'razor_pay'), // Replace with actual plan ID
                'customer_notify' => 1,
                'total_count'     => 12,
            ];

            $response         = Http::withBasicAuth(setting('razor_pay_key'), setting('razor_pay_secret'))->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.razorpay.com/v1/subscriptions', $subscriptionData)->json();

            $data             = [
                'key'             => setting('razor_pay_key'),
                'success'         => true,
                'name'            => setting('system_name'),
                'description'     => $plan->name,
                'image'           => $image,
                'subscription_id' => $response['id'],
                'callback_url'    => route('client.razor.pay.payment.success'),
                'prefill'         => [
                    'name'    => auth()->user()->name,
                    'email'   => auth()->user()->email,
                    'contact' => auth()->user()->phone,
                ],
                'notes'           => [
                    'address' => 'Subscribed to plan ' . $plan->name,
                ],
                'theme'           => [
                    'color' => setting('primary_color'),
                ],
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function razorPaySuccess(Request $request): RedirectResponse
    {
        try {
            $subscription_id = $request->razorpay_subscription_id;
            $response        = Http::withBasicAuth(setting('razor_pay_key'), setting('razor_pay_secret'))->withHeaders([
                'Content-Type' => 'application/json',
            ])->get("https://api.razorpay.com/v1/subscriptions/$subscription_id")->json();
            $payment_details = $response;
            $billingInfo     = session('billing_info');
            $package         = $this->planRepository->find($billingInfo['plan_id']);
            if (!in_array($payment_details['status'], ['created', 'active'])) {
                Toastr::error('invalid_request');

                return redirect()->back();
            }
            $this->subscriptionRepository->create($package, $billingInfo['trx_id'], $payment_details, $billingInfo, false, 'razor_pay');
            session()->forget('billing_info');
            Toastr::success('purchased_successfully');

            return redirect()->route('client.dashboard');
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }

    public function mercadopagoRedirect(Request $request): Redirector|RedirectResponse|Application|null
    {
        try {
            $this->billingData($request);
            $plan               = $this->planRepository->find($request->plan_id);
            $data['amount']     = $plan->price;
            $active_currency    = $this->activeCurrencyCheck();
            $currency_converter = $this->currencyAmountCalculator(null, $data, $active_currency, $this->getCurrency('BRL'));
            $payload            = [
                'auto_recurring' => [
                    'frequency'          => 1,
                    'frequency_type'     => 'months',
                    'transaction_amount' => round($currency_converter['total_amount'], 2),
                    'currency_id'        => 'BRL',
                ],
                'back_url'       => url()->previous(),
                'reason'         => 'Yoga classes',
            ];
            $headers            = [
                'Authorization' => 'Bearer ' . setting('mercadopago_access_key'),
                'Content-Type'  => 'application/json',
            ];
            $response           = httpRequest('https://api.stripe.com/v1/checkout/sessions', $payload, $headers);
            $url                = $response['init_point'];

            return redirect($url);
        } catch (Exception $e) {
            dd($e);
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }

    public function mercadopagoSuccess(Request $request): RedirectResponse
    {
        try {
            $subscription_id = $request->subscription_id;
            $headers            = [
                'Authorization' => 'Bearer ' . setting('mercadopago_access_key'),
                'Content-Type'  => 'application/json',
            ];
            $response        = httpRequest("https://api.mercadopago.com/preapproval_plan/$subscription_id", [], $headers, false, 'GET');
            $payment_details = $response;
            $billingInfo     = session('billing_info');
            $package         = $this->planRepository->find($billingInfo['plan_id']);
            if (!in_array($payment_details['status'], ['authorized', 'active'])) {
                Toastr::error('invalid_request');

                return redirect()->back();
            }
            $this->subscriptionRepository->create($package, $billingInfo['trx_id'], $payment_details, $billingInfo, false, 'razor_pay');
            session()->forget('billing_info');
            Toastr::success('purchased_successfully');

            return redirect()->route('client.dashboard');
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }

    public function stopRecurring($id): JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->subscriptionRepository->stopRecurring($id);

            $data = [
                'status'    => 'success',
                'message'   => __('recurring_stopped_successfully'),
                'title'     => __('success'),
                'is_reload' => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'    => 'success',
                'message'   => __('something_went_wrong_please_try_again'),
                'title'     => __('error'),
                'is_reload' => false,
            ];

            return response()->json($data);
        }
    }

    public function enableRecurring($id): JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->subscriptionRepository->enableRecurring($id);

            $data = [
                'status'    => 'success',
                'message'   => __('recurring_enable_successfully'),
                'title'     => __('success'),
                'is_reload' => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'    => 'success',
                'message'   => __('something_went_wrong_please_try_again'),
                'title'     => __('error'),
                'is_reload' => false,
            ];

            return response()->json($data);
        }
    }

    public function cancelSubscription($id): JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->subscriptionRepository->cancelSubscription($id);

            $data = [
                'status'    => 'success',
                'message'   => __('cancelled_successfully'),
                'title'     => __('success'),
                'is_reload' => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'    => 'success',
                'message'   => __('something_went_wrong_please_try_again'),
                'title'     => __('error'),
                'is_reload' => false,
            ];

            return response()->json($data);
        }
    }
}
