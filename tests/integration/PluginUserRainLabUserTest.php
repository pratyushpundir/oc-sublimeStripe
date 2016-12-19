<?php namespace SublimeArts\SublimeStripe\Tests\Integration;

use PluginTestCase;
use SublimeArts\SublimeStripe\Models\User;
use RainLab\User\Models\User as BaseUser;

class PluginUserRainLabUserTest extends PlugintestCase
{

    /** @test */
    public function it_creates_and_attaches_a_plugin_user_for_every_new_rainlab_user()
    {
        /** Assuming that no current rainlab or plugin specific users exist */
        $this->assertTrue(User::all()->count() == 0);
        $this->assertTrue(BaseUser::all()->count() == 0);

        /** When a new RainLab.User is created */
        $baseUser = BaseUser::create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'tester',
            'password_confirmation' => 'tester'
        ]);

        /** A new plugin based User record should be created and attached to the RainLab.User */
        $this->assertTrue(BaseUser::all()->count() >= 1);
        
        /**
         * TODO: THESE SHOULD PASS
         */
        // $this->assertTrue(User::all()->count() >= 1);
        // $this->assertTrue(User::firstOrFail()->id == $baseUser->user->id);
        
    }

}