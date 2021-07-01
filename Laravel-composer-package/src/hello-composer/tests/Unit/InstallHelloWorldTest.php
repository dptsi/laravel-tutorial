<?php

namespace maximuse\HelloWorld\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use maximuse\HelloWorld\Tests\TestCase;

class InstallHelloWorldTest extends TestCase
{
    /** @test */
    function the_install_command_copies_the_configuration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('helloworld.php'))) {
            unlink(config_path('helloworld.php'));
        }

        $this->assertFalse(File::exists(config_path('helloworld.php')));

        Artisan::call('helloworld:install');

        $this->assertTrue(File::exists(config_path('helloworld.php')));
    }
}
