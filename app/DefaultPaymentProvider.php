<?php

namespace Rowles;

use Stripe\StripeClient;

class DefaultPaymentProvider implements PaymentProviderInterface
{
    /**
     * @var StripeClient $client
     */
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

    /**
     * @return StripeClient
     */
    public function get(): StripeClient
    {
        return $this->client;
    }
}
