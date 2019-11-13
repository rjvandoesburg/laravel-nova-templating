<?php

namespace Rjvandoesburg\NovaTemplating\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Rjvandoesburg\NovaTemplating\Http\Controllers\Api\TemplateController;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::macro('novaResourceTemplateRoute', function () {
            Route::any('/template-api/{resource}/{resourceId}', [TemplateController::class, 'resource']);
        });
    }
}
