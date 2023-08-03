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
        $this->loadMigrationsFrom(
            __DIR__ . '/database/migrations'
        );
    }
}