<?php

// Pastikan folder bootstrap cache tiruan ada di /tmp dan bisa ditulis
if (isset($_ENV['VERCEL_URL'])) {
    if (!file_exists('/tmp/bootstrap/cache')) {
        mkdir('/tmp/bootstrap/cache', 0755, true);
    }
    
    // Salin file manifes bawaan jika ada agar Laravel tidak mencoba menulis dari awal
    $originalCache = __DIR__ . '/../bootstrap/cache';
    if (file_exists($originalCache)) {
        foreach (scandir($originalCache) as $file) {
            if ($file !== '.' && $file !== '..') {
                copy("$originalCache/$file", "/tmp/bootstrap/cache/$file");
            }
        }
    }
}

// Mengarahkan Vercel untuk membaca file index.php bawaan Laravel
require __DIR__ . '/../public/index.php';