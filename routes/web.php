<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/dashboard/connectors', function () {
        return view('dashboard.connectors');
    })->name('dashboard.connectors');
});

require __DIR__.'/auth.php';
