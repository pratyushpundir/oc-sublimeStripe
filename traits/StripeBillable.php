<?php namespace SublimeArts\SublimeStripe\Traits;

use SublimeArts\SublimeStripe\Models\Settings;
use SublimeArts\SublimeStripe\Models\SingleCharge;
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
        $stripeCustomer = Customer::create([
            'email' => $this->baseUser->email,
            'source' => $stripeToken
        ]);

        $stripeCharge = Charge::create([
            'customer' => $stripeCustomer->id,
            'amount' => $product->{Settings::get('amount_attribute')},
            'currency' => 'usd'
        ]);

        $this->activate($stripeCustomer->id);
        
        $localCharge = SingleCharge::create([
            'stripe_charge_id' => $stripeCharge->id
        ]);

        $localCharge->product = $product;

        $this->singleCharges()->add($localCharge);
    }

}
