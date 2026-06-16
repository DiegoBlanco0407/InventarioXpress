<?php

use Illuminate\Support\Facades\Route;

// SPA fallback: exclude API paths so they are never captured by the catch-all
Route::get('/{any}', function () {
    return view('app'); // este NOMBRE debe ser app SIN extensión .blade.php
})->where('any', '^(?!api\/).*');

