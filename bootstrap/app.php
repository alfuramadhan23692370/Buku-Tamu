<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// --- MODIFIKASI KHUSUS VERCEL (LARAVEL 10) ---
// Jika berjalan di server Vercel, paksa folder cache & storage pindah ke /tmp
if (isset($_ENV['VERCEL_URL'])) {
    $app->useStoragePath('/tmp/storage');
    
    $app->bind('path.bootstrap', function () {
        return '/tmp/bootstrap';
    });
    
    // Buat folder bootstrap/cache tiruan secara otomatis jika belum ada
    if (!file_exists('/tmp/bootstrap/cache')) {
        mkdir('/tmp/bootstrap/cache', 0755, true);
    }
}
//-----------------------------------------------

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
*/

return $app;