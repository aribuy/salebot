<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CacneclSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscription;
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payment_method = $this->subscription->payment_method;

        if ($payment_method == 'stripe') {
            $this->cancelStripe($this->subscription);
        } elseif ($payment_method == 'paddle') {
            $this->cancelPaddle($this->subscription);
        } elseif ($payment_method == 'paypal') {
            $this->cancelPaypal($this->subscription);
        }
    }

    public function cancelStripe($subscription)
    {
        $stripe_subscript_id = $subscription->payment_details['subscription'];
        $headers             = [
            'Authorization' => 'Basic '.base64_encode(setting('stripe_secret').':'),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];

        $data                = [
            'invoice_now' => 'false',
        ];

        return httpRequest('https://api.stripe.com/v1/subscriptions/'.$stripe_subscript_id, $data, $headers, true, 'DELETE');
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
}
