<?php

namespace Rjvandoesburg\NovaTemplating;

use Illuminate\Support\ServiceProvider;

class NovaTemplatingServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(Providers\RouteServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
