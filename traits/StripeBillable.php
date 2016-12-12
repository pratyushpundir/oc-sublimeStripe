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
    /**
     * Checks if the given user is active on Stripe
     * @return boolean
     */
    public function isActive()
    {
        return (!! $this->stripe_active);
    }

    /**
     * Sets the Stripe ID and Stripe Active properties on the User instance
     * @param  String $stripeId Stripe ID for the user
     */
    public function activate($stripeId = null)
    {
        $this->update([
            'stripe_id' => ($stripeId) ? $stripeId : $this->stripe_id,
            'stripe_active' => true
        ]);

        Log::info($this->baseUser->email . " was activated!");
    }

    /**
     * Sets the Stripe Active property to false for the given User.
     */
    public function deactivate()
    {
        $this->update([
            'stripe_active' => false
        ]);

        Log::info($this->baseUser->email . " was deactivated!");
    }

    /**
     * Find a User by their Stripe ID
     * @param  String $stripeId The Stripe ID
     * @return User             The User with the given Stripe ID
     */
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
        /** Stripe Stuff */
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
