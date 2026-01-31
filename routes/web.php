<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth')->name('dashboard');

Route::get('/dashboard/connectors', function () {
    return view('dashboard.connectors');
})->middleware('auth')->name('dashboard.connectors');
