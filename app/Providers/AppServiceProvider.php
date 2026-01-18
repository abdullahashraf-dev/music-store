<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenApi\Attributes as OA;

use Laravel\Passport\Passport;

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Bearer token authentication',
)]
class AppServiceProvider extends ServiceProvider
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
        Passport::$validateKeyPermissions = false;
    }
}
