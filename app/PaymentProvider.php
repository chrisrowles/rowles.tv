<?php

namespace App;

use Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

class PaymentProvider
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    public function createPaymentIntent()
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => 999,
                'currency' => 'gbp',
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);
        } catch (ApiErrorException $e) {
            Log::error($e->getMessage());

            return false;
        }

        return $intent;
    }

    public function retrievePaymentIntent(int $id)
    {
        try {
            return PaymentIntent::retrieve($id);
        } catch (ApiErrorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function setPaymentIntentForSession(PaymentIntent $intent)
    {
        session()->put('payment_intent', $intent);
    }

    public function clearPaymentIntentFromSession()
    {
        session()->forget('payment_intent');
    }
}
