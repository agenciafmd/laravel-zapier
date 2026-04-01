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
        $this->registerLogChannel();
    }

    private function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-zapier.php', 'laravel-zapier');
    }

    private function registerLogChannel(): void
    {
        $this->app->make('config')->set('logging.channels.zapier', array_merge([
            'driver' => 'daily',
            'path' => storage_path('logs/zapier.log'),
            'level' => 'debug',
        ], $this->app->make('config')->get('logging.channels.zapier', [])));
    }
}
