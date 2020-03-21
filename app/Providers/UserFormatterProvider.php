<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Formatters\User as UserFormatter;

class UserFormatterProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserFormatter::class, function ($app) {
            return new UserFormatter();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
