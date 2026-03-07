<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //configuramos el middleware para redirigir a los clientes no autenticados a la ruta de login, pero solo para las rutas web. Para las rutas API, devolveremos una respuesta JSON con un mensaje de error y un código de estado 401.
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('api/*')) {
                return null;
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof AuthenticationException) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
                Log::error('Ocurrio un error' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
            }
        });
    })->create();
