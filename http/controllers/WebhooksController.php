<?php namespace SublimeArts\SublimeStripe\Http\Controllers;

use SublimeArts\SublimeStripe\Models\SingleCharge;
use SublimeArts\SublimeStripe\Models\User;
use Illuminate\Routing\Controller;
use Log;

class WebhooksController extends Controller {

    /**
     * Handle the webhook event
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
     * Handle a Subscription Deletion
     */
    public function whenCustomerSubscriptionDeleted($payload)
    {

        $this->getUser($payload)->deactivate();

    }

    /**
     * Record a successful stripe payment
     */
    public function whenChargeSucceeded($payload)
    {
        $charge = $this->getUser($payload)->singleCharges()->where('stripe_charge_id', $payload['data']['object']['id'])->firstOrFail();
        $charge->update([
            'amount_in_cents' => $payload['data']['object']['amount'],
            'stripe_invoice' => $payload['data']['object']['invoice']
        ]);
    }

    /**
     * Get a Member using Stripe Payload
     */
    protected function getUser($payload)
    {
        return User::byStripeId(
            $payload['data']['object']['customer']
        );
    }

    /**
     * Convert a Stripe event to a method name
     */
    public function eventToMethod($event)
    {
        return 'when' . studly_case(implode(explode('.', $event), '_'));
    }

}
