<?php namespace SublimeArts\SublimeStripe\Tests\Unit\Traits;

require "vendor/autoload.php";

use PluginTestCase;
use SublimeArts\SublimeStripe\Traits\StripeBillable;
use SublimeArts\SublimeStripe\Models\User;
use SublimeArts\SublimeStripe\Models\Settings;
use SublimeArts\SublimeStripe\Models\SingleCharge;
use RainLab\User\Models\User as BaseUser;
use Stripe\Stripe;
use Stripe\Token;

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
        // $productModelClass = Settings::productModelClass();
        // $id = Settings::get('id_attribute');
        // $name = Settings::get('name_attribute');
        // $description = Settings::get('description_attribute');
        // $amount = Settings::get('amount_attribute');

        // $this->product = $productModelClass::create([
        //     $id => 1,
        //     $name => 'A Fake Product',
        //     $description => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
        //     $amount => 4999,
        // ]);

        /** Setup Stripe stuff */
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function tearDown()
    {
        BaseUser::truncate();
        User::truncate();
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
        $this->assertFalse($this->user->isStripeActive());

        $this->user->activate();
        $this->assertFalse($this->user->isStripeActive());

        $this->user->activate('fake_stripe_id');
        $this->assertTrue($this->user->isStripeActive());

        $this->user->deactivate();
        $this->assertFalse($this->user->isStripeActive());
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
    public function it_records_a_stripe_charge_for_a_given_product()
    {
        $this->user->recordStripeCharge(1, 'fake_stripe_charge_id');
        
        $chargeExists = $this->user->singleCharges()
                                   ->where('stripe_charge_id', 'fake_stripe_charge_id')
                                   ->first();

        $this->assertTrue(!! $chargeExists);
        $this->assertEquals($chargeExists->product_id, 1);
    }

}
