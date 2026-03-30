<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        Response::macro('apiSuccess', function ($data = null, string $message = 'Success', int $status = 200) {
            return Response::json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data,
            ], $status);
        });
    }
}
