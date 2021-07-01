<?php

namespace maximuse\HelloWorld\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        # Set-up tambahan seperti inisialisasi Model.
    }

    protected function getPackageProviders($app)
    {
        return [
            # CustomServiceProvider.class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        # Implementasi setting-up environment
    }
}
