<?php

namespace Rowles;

use Stripe\StripeClient;

class PaymentProvider implements PaymentProviderInterface
{
    public StripeClient $client;

    /**
     * PaymentProvider constructor.
     *
     * @param $key
     */
    public function __construct($key)
    {
        $this->client = new StripeClient($key);
    }
}
