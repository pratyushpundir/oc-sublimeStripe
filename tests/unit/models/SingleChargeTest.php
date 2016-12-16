<?php namespace SublimeArts\SublimeStripe\Tests\Unit\Models;

use PluginTestCase;
use Exception;
use SublimeArts\SublimeStripe\Models\SingleCharge;

class SingleChargeTest extends PluginTestCase
{

    public function tearDown()
    {
        SingleCharge::truncate();
        parent::tearDown();
    }

    /** @test */
    public function it_returns_a_record_by_its_stripe_charge_id()
    {
        $createdCharge1 = SingleCharge::create([
            'stripe_charge_id' => 'fake_charge_id'
        ]);

        $createdCharge2 = SingleCharge::create([
            'stripe_charge_id' => 'another_fake_charge_id'
        ]);

        $retrievedCharge1 = SingleCharge::byChargeId('another_fake_charge_id');
        $retrievedCharge2 = SingleCharge::byChargeId('fake_charge_id');

        $this->assertTrue($retrievedCharge1->id != $retrievedCharge2->id);
        $this->assertEquals($createdCharge2->id, $retrievedCharge1->id);
        $this->assertEquals($createdCharge1->id, $retrievedCharge2->id);
    }

}