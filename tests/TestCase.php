<?php

namespace Agenciafmd\Zapier\Tests;

use Agenciafmd\Zapier\Providers\ZapierServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ZapierServiceProvider::class,
        ];
    }
}
