<?php

use App\Http\Controllers\MetaWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/meta', [MetaWebhookController::class, 'verify'])->name('meta.verify');
Route::post('/meta', [MetaWebhookController::class, 'handle'])->name('meta.handle');
