<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCharge($amount, $currency, $source, $customerName, $customerAddress)
    {
        try {
            return Charge::create([
                'amount' => $amount,
                'currency' => $currency,
                'source' => $source,
                'description' => 'Payment for Order #1234',
                'shipping' => [
                    'name' => $customerName,
                    'address' => $customerAddress,
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw $e;
        }
    }
}
