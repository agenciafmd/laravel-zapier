<?php

namespace Agenciafmd\Zapier\Providers;

use Illuminate\Support\ServiceProvider;

class ZapierServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->registerConfigs();
    }

    private function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-zapier.php', 'laravel-zapier');
    }
}
