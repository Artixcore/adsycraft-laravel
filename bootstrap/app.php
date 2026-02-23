<?php

use App\Http\Middleware\AddRequestId;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('webhooks')->group(base_path('routes/webhooks.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard'));
        $middleware->alias(['role' => EnsureUserHasRole::class]);
        $middleware->api(prepend: [AddRequestId::class]);
        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return null;
            }

            $status = 500;
            $message = 'Server error. Please try again.';
            $errors = null;

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $status = 422;
                $message = $e->getMessage();
                $errors = $e->errors();
            } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                $status = 401;
                $message = 'Unauthenticated. Please log in again.';
            } elseif ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                $status = 403;
                $message = $e->getMessage() ?: 'Forbidden.';
            } elseif ($e instanceof \Illuminate\Session\TokenMismatchException) {
                $status = 419;
                $message = 'Session expired. Please refresh and try again.';
            } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $status = 404;
                $message = 'Record not found.';
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $status = $e->getStatusCode();
                $message = $e->getMessage() ?: 'Request failed.';
            } elseif ($e instanceof \App\Exceptions\AIConfigurationException) {
                $status = 503;
                $message = $e->getMessage();
            }

            if ($status >= 500) {
                Log::error($e->getMessage(), ['exception' => $e]);
            }

            return response()->json([
                'ok' => false,
                'message' => $message,
                'errors' => $errors,
            ], $status);
        });
    })->create();
