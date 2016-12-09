<?php namespace SublimeArts\SublimeStripe\Traits;

use SublimeArts\SublimeStripe\Models\Subscription;
use SublimeArts\SublimeStripe\Models\SingleCharge;
use SublimeArts\SublimeStripe\Models\Payment;
use SublimeArts\SublimeStripe\Models\Settings;
use Stripe\Customer;
use Stripe\Charge;
use Carbon\Carbon;
use Log, Exception;

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

        Log::info($this->baseUser->email . " was activated!");
    }

    public function deactivate()
    {
        $this->update([
            'stripe_active' => false,
            'plan_ends_at' => Carbon::now(),
        ]);

        Log::info($this->baseUser->email . " was deactivated!");
    }

    public static function byStripeId($stripeId)
    {
        return static::where('stripe_id', $stripeId)->firstOrFail();
    }

    /**
     * Create a Stripe customer and apply a single charge for the given product.
     * @param Product $product    Product instance based on the class set in plugin Settings
     * @param String $stripeToken StripeToken
     */
    public function addSingleCharge($product, $stripeToken)
    {
        $customer = Customer::create([
            'email' => $this->baseUser->email,
            'source' => $stripeToken
        ]);

        Charge::create([
            'customer' => $customer->id,
            'amount' => $product->{Settings::get('amount_attribute')},
            'currency' => 'usd'
        ]);

        $this->activate($customer->id);

        /** TODO: Make the below code work with webhooks from Stripe */
        $charge = SingleCharge::create();
        $payment = Payment::create([
            'amount_in_cents' => $product->{Settings::get('amount_attribute')},
            'product_name' => $product->{Settings::get('name_attribute')}
        ]);
        $charge->payment = $payment;
        $charge->product = $product;
        
        $this->singleCharges()->add($charge);
    }

}
