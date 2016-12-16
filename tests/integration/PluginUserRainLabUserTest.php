<?php namespace SublimeArts\SublimeStripe\Tests\Integration;

use PluginTestCase;
use SublimeArts\SublimeStripe\Models\User;
use RainLab\User\Models\User as BaseUser;

class PluginUserRainLabUserTest extends PlugintestCase
{

    /** @test */
    // public function it_creates_and_attaches_a_plugin_user_for_every_new_rainlab_user()
    // {
    //     /** Given that no current plugin specific users exist */
    //     $users = User::all();
    //     $this->assertTrue($users->count() == 0);

    //     /** When a new RainLab.User is created */
    //     $baseUser = BaseUser::create([
    //         'name' => 'John Doe',
    //         'email' => 'john@doe.com',
    //         'password' => 'tester',
    //         'password_confirmation' => 'tester'
    //     ]);

    //     /** A new plugin based User record should be created and attached to the RainLab.User */
    //     $this->assertTrue($baseUser->user());
    // }

}