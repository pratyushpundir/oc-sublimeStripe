<?php namespace SublimeArts\SublimeStripe\Http\Controllers;

use SublimeArts\SublimeStripe\Models\SingleCharge;
use SublimeArts\SublimeStripe\Models\User;
use Illuminate\Routing\Controller;
use Log;

class WebhooksController extends Controller {

    /**
     * Route the handling of the incoming webhook event to the required method
     * @return Http\Response
     */
    public function handle()
    {

        $payload = request()->all();
        $method = $this->eventToMethod($payload['type']);

        if ( method_exists($this, $method) ) {
            $this->$method($payload);
            return response('Webhook Handled!');
        }

        return response('Webhook NOT Handled!');

    }

    /**
     * Handle a Subscription deleted at Stripe's end
     * @param array $payload Payload received with the Stripe event
     */
    public function whenCustomerSubscriptionDeleted($payload)
    {

        $this->getUser($payload)->deactivate();

    }

    /**
     * Record a successful stripe payment
     * @param array $payload Payload received with the Stripe event
     */
    public function whenChargeSucceeded($payload)
    {
        $stripeChargeId = $payload['data']['object']['id'];
        
        $charge = $this->getUser($payload)->singleCharges()->where('stripe_charge_id', $stripeChargeId)->firstOrFail();

        $charge->update([
            'amount_in_cents' => $payload['data']['object']['amount'],
            'stripe_invoice' => $payload['data']['object']['invoice']
        ]);

        Log::info("Stripe Charge with id {$stripeChargeId} recorded in database successfully.");
    }

    /**
     * Get a User using Stripe Payload
     * @param array $payload Payload received with the Stripe event
     * @return RainLab\User\Models\User
     */
    protected function getUser($payload)
    {
        return User::byStripeId(
            $payload['data']['object']['customer']
        );
    }

    /**
     * Convert a Stripe event to a method name
     * @param  String $event Event name received from Stripe
     * @return String        Handler method name that should handle this event
     */
    public function eventToMethod($event)
    {
        return 'when' . studly_case(implode(explode('.', $event), '_'));
    }

}
