<?php namespace SublimeArts\SublimeStripe\Traits;

use SublimeArts\SublimeStripe\Classes\Subscription;
use SublimeArts\SublimeStripe\Classes\SingleCharge;
use Carbon\Carbon;
use Log;

/**
 * Provides a collection of methods that allow a User model to be billed using Stripe.
 * Allows for creation of both 'Subsriptions' and 'Single Charges'.
 */
trait StripeBillable
{
    public function isActive()
    {
        return (!! $this->stripe_active);
    }

    public function activate($stripeId = null)
    {
        $this->update([
            'stripe_id' => ($stripeId) ? $stripeId : $this->stripe_id,
            'stripe_active' => true
        ]);

        Log::info($this->user->email . " was activated!");
    }

    public function deactivate()
    {
        $this->update([
            'stripe_active' => false,
            'plan_ends_at' => Carbon::now(),
        ]);

        Log::info($this->user->email . " was deactivated!");
    }

    public static function byStripeId($stripeId)
    {
        return static::where('stripe_id', $stripeId)->firstOrFail();
    }

    public function subscriptionPayments()
    {
        return $this->subscription()->payments;
    }

    public function singleChargePayments()
    {
        $singleCharges = $this->singleCharges;
        $payments = [];

        foreach ( $singleCharge->payment->get() as $payment )
        {
            array_push($payments, $payment);
        }

        return collect($payments);
    }

}
