<?php

// Buat folder cache otomatis di direktori /tmp milik Vercel
use Illuminate\Foundation\Application;

if (!file_exists('/tmp/bootstrap/cache')) {
    mkdir('/tmp/bootstrap/cache', 0755, true);
}

require __DIR__ . '/../public/index.php';