<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// --- TAMBAHKAN KODE INI DI SINI ---
// Jika berjalan di Vercel, paksa Laravel memakai folder /tmp untuk cache
if (isset($_ENV['VERCEL_URL'])) {
    $app = new Application($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));
    $app->useStoragePath('/tmp/storage');
    $app->bootstrapWith([
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    ]);
    
    // Paksa pindah bootstrap cache path
    $app->bind('path.bootstrap', function () {
        return '/tmp/bootstrap';
    });
}
// ----------------------------------

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();