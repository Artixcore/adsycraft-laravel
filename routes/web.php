<?php

use App\Http\Controllers\MetaOAuthCallbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-docs', function () {
    return view('api-docs');
})->name('api-docs');

Route::get('/openapi.json', function () {
    $path = storage_path('app/openapi.json');

    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
})->name('openapi.spec');

Route::get('/connectors/meta/callback', MetaOAuthCallbackController::class)->name('connectors.meta.callback');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/dashboard/connectors', function () {
        return view('dashboard.connectors');
    })->name('dashboard.connectors');
});

require __DIR__.'/auth.php';
