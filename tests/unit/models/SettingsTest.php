<?php namespace SublimeArts\SublimeStripe\Tests\Unit\Models;

use PluginTestCase;
use Exception;
use SublimeArts\SublimeStripe\Models\Settings;

class SettingsTest extends PluginTestCase
{

    public function tearDown()
    {
        Settings::truncate();
        parent::tearDown();
    }

    /** @test */
    public function it_throws_an_exception_if_required_settings_are_not_set()
    {
        $this->expectException(Exception::class);
        Settings::checkRequired();
    }

    /** TODO: THIS IS NOT BEHAVING AS EXPECTED. REFACTOR REQUIRED */
    /** @test */
    // public function it_returns_true_only_if_all_required_settings_have_been_set()
    // {
    //     Settings::set('stripe_publishable_key', 'fake_string');
    //     Settings::set('stripe_secret_key', 'fake_string');
    //     // Settings::set('product_model', 'fake_string');
    //     // Settings::set('id_attribute', 'fake_string');
    //     // Settings::set('name_attribute', 'fake_string');
    //     // Settings::set('description_attribute', 'fake_string');
    //     // Settings::set('amount_attribute', null);
    //     // Settings::set('container_html_element', 'fake_string');
    //     // Settings::set('post_uri', 'fake_string');
    //     // Settings::set('redirect_uri', 'fake_string');
    //     // Settings::set('button_classes', 'fake_string');
    //     $this->assertTrue(Settings::checkRequired());
    // }

    /** @test */
    public function it_returns_a_fully_qualified_class_name_for_the_product_model_class()
    {
        Settings::set('product_model', 'Some\Random\Class\Name');
        $this->assertEquals(Settings::productModelClass(), '\Some\Random\Class\Name');
        
        Settings::set('product_model', '   \Some\Random\Class\Name   ');
        $this->assertEquals(Settings::productModelClass(), '\Some\Random\Class\Name');
    }

    /** @test */
    public function it_returns_a_fully_qualified_class_name_for_the_user_model_class()
    {
        Settings::set('user_model', 'Some\Random\Class\Name');
        $this->assertEquals(Settings::userModelClass(), '\Some\Random\Class\Name');
        
        Settings::set('user_model', '   \Some\Random\Class\Name   ');
        $this->assertEquals(Settings::userModelClass(), '\Some\Random\Class\Name');
    }

}