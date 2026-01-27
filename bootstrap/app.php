<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'userAkses' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            // Jika error adalah BapException, biarkan handle sendiri 
            if ($e instanceof \App\Exceptions\BapException) {
                return null; // Laravel akan memanggil method render() di BapException
            }

            // Handle Error Umum (500) agar User tidak melihat layar merah
            if ($request->isMethod('post') || $request->ajax()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan sistem. Silakan hubungi admin.',
                    'error' => app()->isProduction() ? null : $e->getMessage()
                ], 500);
            }
            
            // Untuk request biasa, biarkan default Laravel (atau return view error custom)
            return null;
        });
    })->create();
