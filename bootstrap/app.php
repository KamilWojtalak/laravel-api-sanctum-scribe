<?php

use App\Services\Api\V1\RenderErrors;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/v1/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exceptions $e, Request $request) {

            // Możesz sprawdzić czy jeżeli jest $e instance of Validation Exception, to wszystkie $e->errors() będziesz logować

            // NOTE Tutaj nadpisujesz error reporting dla
            return (new RenderErrors)->error([
                [
                    'type' => $e::class,
                    'status' => 0,
                    'message' => $e->getMessage(),
                ]
            ]);
        });
    })->create();
