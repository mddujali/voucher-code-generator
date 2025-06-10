<?php

use App\Exceptions\Json\HttpJsonException;
use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware('api')
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if ($request->is('api/*')) {
                $status = Response::HTTP_UNAUTHORIZED;
                $errorCode = studly(Response::$statusTexts[$status]);
                $message = period_at_the_end(__('shared.http.' . $status));
                $errors = [];

                return (new class () {
                    use ResponseTemplate;
                })->errorResponse(
                    status: $status,
                    errorCode: $errorCode,
                    message: $message,
                    errors: $errors,
                );
            }
        });

        $exceptions->render(function (HttpJsonException $exception, Request $request) {
            if ($request->is('api/*')) {
                $status = $exception->getStatus() !== 419
                    ? $exception->getStatus()
                    : Response::HTTP_FORBIDDEN;
                $errorCode = !blank($exception->getErrorCode())
                    ? $exception->getErrorCode()
                    : studly(Response::$statusTexts[$status]);
                $message = !blank($exception->getMessage())
                    ? $exception->getMessage()
                    : Response::$statusTexts[$status];
                $errors = $exception->getErrors();

                return (new class () {
                    use ResponseTemplate;
                })->errorResponse(
                    status: $status,
                    errorCode: $errorCode,
                    message: $message,
                    errors: $errors,
                );
            }
        });
    })->create();
