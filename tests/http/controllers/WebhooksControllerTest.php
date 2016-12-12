<?php namespace SublimeArts\SublimeStripe\Tests\Http\Controllers;

use PluginTestCase;
use SublimeArts\SublimeStripe\Http\Controllers\WebhooksController;

class WebhooksControllerTest extends PluginTestCase
{

    protected $baseUrl = 'http://nasmei.org.dev';

    /** @test */
    public function it_converts_a_stripe_event_name_to_a_method_name()
    {
        $name = (new WebhooksController)->eventToMethod('customer.subscription.deleted');

        $this->assertEquals('whenCustomerSubscriptionDeleted', $name);
    }

}
