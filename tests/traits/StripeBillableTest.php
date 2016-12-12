<?php namespace SublimeArts\SublimeStripe\Tests\Traits;

use PluginTestCase;
use SublimeArts\SublimeStripe\Traits\StripeBillable;
use SublimeArts\SublimeStripe\Models\User;
use SublimeArts\SublimeStripe\Models\Settings;
use SublimeArts\SublimeStripe\Models\SingleCharge;
use RainLab\User\Models\User as BaseUser;
// use \Stripe\Stripe;
// use \Stripe\Customer;
// use \Stripe\Charge;
// use \Stripe\Token;

class StripeBillableTest extends PluginTestCase
{
    protected $baseUser;
    protected $user;
    protected $product;
    protected $stripeToken;

    public function setUp()
    {
        parent::setUp();

        /** The RainLab User */
        $this->baseUser = BaseUser::create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'tester',
            'password_confirmation' => 'tester'
        ]);

        /** The Sublime Stripe User */
        $this->user = User::create([
            'base_user_id' => $this->baseUser->id
        ]);

        /** A Dummy Product */
        $this->product = [
            'id' => 1,
            'name' => 'A Fake Product',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
            'amount' => 4999,
        ];

        /** A Stripe Token */
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // $this->stripeToken = Token::create([
        //     'card' => [
        //         'number' => '4242424242424242',
        //         'exp_month' => 12,
        //         'exp_year' => 2020,
        //         'cvc' => '123'
        //     ]
        // ]);
    }

    public function tearDown()
    {
        $this->user->forceDelete();
        $this->baseUser->forceDelete();
        $this->product = null;
        $this->stripeToken = null;
        parent::tearDown();
    }

    /** @test */
    public function it_activates_a_stripe_user()
    {
        /** User is not active to begin with */
        $this->assertFalse($this->user->stripe_active && $this->user->stripe_id);

        /** Manually activate a User */
        $this->user->activate();

        /** The stripe_active property has been set? */
        $this->assertTrue($this->user->stripe_active);
        
        /** A Stripe ID is required for a User to be deemed active */
        $this->assertFalse($this->user->stripe_active && $this->user->stripe_id);
        $this->user->activate('fake_stripe_id');
        $this->assertTrue($this->user->stripe_active && $this->user->stripe_id);
    }

    /** @test */
    public function it_deactivates_a_stripe_user()
    {
        /** A manually activated User */
        $this->user->activate('fake_stripe_id');
        $this->assertTrue($this->user->stripe_active && $this->user->stripe_id);

        /** Deactivate the User manually */
        $this->user->deactivate();
        $this->assertFalse($this->user->stripe_active);
        $this->assertFalse($this->user->stripe_active && $this->user->stripe_id);
    }

    /** @test */
    public function it_returns_stripe_active_status()
    {
        $this->assertFalse($this->user->isActive());

        $this->user->activate();
        $this->assertFalse($this->user->isActive());

        $this->user->activate('fake_stripe_id');
        $this->assertTrue($this->user->isActive());

        $this->user->deactivate();
        $this->assertFalse($this->user->isActive());
    }

    /** @test */
    public function it_finds_a_user_by_the_stripe_id()
    {
        $this->user->update([
            'stripe_id' => 'fake_stripe_id'
        ]);

        $returnedUser = User::byStripeId('fake_stripe_id');

        $this->assertEquals($this->user->id, $returnedUser->id);
    }

    /** @test */
    // public function it_adds_a_single_stripe_charge()
    // {
    //     // $this->user->addSingleCharge($this->product, $this->stripeToken);
    //     $this->assertTrue(class_exists('Stripe\Stripe'));
    // }

}
