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
    public function isStripeActive()
    {
        return (!! $this->stripe_active && $this->stripe_id != null);
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
     * @param  integer $productId    Product's ID
     * @param  String  $stripeToken  StripeToken
     * @return SingleCharge          Local SingleCharge Instance
     */
    public function addSingleCharge($productId, $stripeToken)
    {
        $product = Settings::getProductById($productId);
        $amount = $product->{Settings::get('amount_attribute')};
        
        /** Attempt to charge on Stripe */
        $stripeCharge = $this->attemptStripeCharge($amount, $stripeToken);

        /** Record charge locally if successfully */
        if ($stripeCharge) {
            $this->activate($stripeCharge['stripe_customer_id']);
            return $this->recordStripeCharge($productId, $stripeCharge['stripe_charge_id']);
        }
    }

    /**
     * Attemps to create a Stripe Customer and apply a charge with the given amount 
     * to that Customer.
     * @param  double $amount       Amount to be charged for
     * @param  String $stripeToken  Stripe Token for the attempt
     * @return array                An array with the Stripe Customer and Charge IDs
     */
    public function attemptStripeCharge($amount, $stripeToken)
    {
        /** Create the Stripe Customer */
        $stripeCustomer = $this->createStripeCustomer($stripeToken);

        /** Charge the Stripe Customer */
        $stripeCharge = Charge::create([
            'customer' => $stripeCustomer->id,
            'amount' => $amount,
            'currency' => 'usd'
        ]);
        
        return [
            'stripe_customer_id' => $stripeCustomer->id,
            'stripe_charge_id' => $stripeCharge->id
        ];
    }

    /**
     * Records a Stripe charge with the given charge ID as a SingleCharge 
     * record in the local database.
     * @param  integer $productId      ID of the ordered product
     * @param  String $stripeChargeId  Stripe Charge ID
     * @return SingleCharge            Newly recorded SingleCharge record
     */
    public function recordStripeCharge($productId, $stripeChargeId)
    {
        $localCharge = SingleCharge::create([
            'stripe_charge_id' => $stripeChargeId,
            'product_id' => $productId
        ]);

        $this->singleCharges()->add($localCharge);

        return $localCharge;
    }

    /**
     * Create a customer on Stripe
     * @param  String $stripeToken      The Stripe Token
     * @return Stripe\Customer | false  Returns the Stripe customer if succesfull else retuns false.
     */
    public function createStripeCustomer($stripeToken) 
    {
        $customer = Customer::create([
            'email' => $this->baseUser->email,
            'source' => $stripeToken
        ]);

        if (!! $customer) {
            return $customer;
        } else {
            return false;
        }
    }

}
