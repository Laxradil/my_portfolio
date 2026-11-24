<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/web_shield/{path?}', function ($path = null) {
    $basePath = realpath(base_path('web_shield'));
    if (($path ?: 'index.html') === 'favicon.ico') {
        $publicFavicon = public_path('favicon.ico');
        if (is_file($publicFavicon)) {
            return response()->file($publicFavicon, ['Content-Type' => 'image/x-icon']);
        }
    }
    $filePath = realpath($basePath . DIRECTORY_SEPARATOR . ($path ?: 'index.html'));

    if (
        $basePath === false
        || $filePath === false
        || !str_starts_with($filePath, $basePath . DIRECTORY_SEPARATOR)
        || !is_file($filePath)
    ) {
        abort(404);
    }

    $mimeType = match (strtolower(pathinfo($filePath, PATHINFO_EXTENSION))) {
        'html' => 'text/html',
        'css' => 'text/css',
        'js' => 'text/javascript',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'pdf' => 'application/pdf',
        default => 'application/octet-stream',
    };

    return response()->file($filePath, ['Content-Type' => $mimeType]);
})->where('path', '.*');
