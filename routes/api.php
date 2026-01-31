<?php

use App\Http\Controllers\Api\AiConnectionController;
use App\Http\Controllers\Api\BusinessAccountController;
use App\Http\Controllers\Api\MetaConnectorController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('businesses', BusinessAccountController::class)->parameters(['businesses' => 'business']);
    Route::post('businesses/{business}/toggle-autopilot', [BusinessAccountController::class, 'toggleAutopilot'])
        ->name('businesses.toggle-autopilot');
    Route::post('businesses/{business}/generate-today', [BusinessAccountController::class, 'generateToday'])
        ->name('businesses.generate-today');
    Route::get('businesses/{business}/posts', [PostController::class, 'index'])->name('businesses.posts');
    Route::get('businesses/{business}/calendar', [PostController::class, 'calendar'])->name('businesses.calendar');

    Route::get('businesses/{business}/ai-connections', [AiConnectionController::class, 'index'])->name('businesses.ai-connections.index');
    Route::post('businesses/{business}/ai-connections', [AiConnectionController::class, 'store'])->name('businesses.ai-connections.store');
    Route::put('businesses/{business}/ai-connections/{connection}', [AiConnectionController::class, 'update'])->name('businesses.ai-connections.update');
    Route::delete('businesses/{business}/ai-connections/{connection}', [AiConnectionController::class, 'destroy'])->name('businesses.ai-connections.destroy');
    Route::post('businesses/{business}/ai-connections/{connection}/make-primary', [AiConnectionController::class, 'makePrimary'])->name('businesses.ai-connections.make-primary');
    Route::post('businesses/{business}/ai-connections/{connection}/test', [AiConnectionController::class, 'test'])->name('businesses.ai-connections.test');

    Route::get('businesses/{business}/connectors/meta/status', [MetaConnectorController::class, 'status'])->name('businesses.connectors.meta.status');
    Route::post('businesses/{business}/connectors/meta/connect', [MetaConnectorController::class, 'connect'])->name('businesses.connectors.meta.connect');
    Route::post('businesses/{business}/connectors/meta/disconnect', [MetaConnectorController::class, 'disconnect'])->name('businesses.connectors.meta.disconnect');
});
