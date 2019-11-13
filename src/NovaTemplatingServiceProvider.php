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
        // Load the nova resources because we need them when rendering the template
        \Nova::resourcesIn(app_path('Nova'));
    }
}
