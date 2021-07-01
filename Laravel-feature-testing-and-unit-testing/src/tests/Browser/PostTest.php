<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PostTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->screenshot('post-home')
                ->assertSee('Post');
        });
    }

    public function testCreateReturnPath()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->clickLink('Create post')
                ->type('title', "TestCreatePost")
                ->type('description', "TestCreatePostDescription")
                ->press('submit-post')
                ->assertPathIs('/post');
        });
    }

    public function testCreateVisible()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->clickLink('Create post')
                ->type('title', "TestCreatePost2")
                ->type('description', "TestCreatePostDescription2")
                ->press('submit-post')
                ->assertSee('TestCreatePost2');
        });
    }
}
