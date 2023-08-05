<?php

namespace Ernandesrs\Lapipay;

use Illuminate\Support\ServiceProvider;

class LapipayServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/lapipay.php' => config_path('lapipay.php'),
        ], 'lapipay-config');

        $this->loadMigrationsFrom(
            __DIR__ . '/database/migrations'
        );

        $this->loadTranslationsFrom(
            __DIR__ . '/lang',
            'lapipay-lang'
        );

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }
}