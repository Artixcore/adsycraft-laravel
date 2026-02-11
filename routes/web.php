<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AutomationController as AdminAutomationController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\MetaAccountController as AdminMetaAccountController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
    Route::get('/meta-accounts', [AdminMetaAccountController::class, 'index'])->name('meta-accounts.index');
    Route::get('/automations', [AdminAutomationController::class, 'index'])->name('automations.index');
    Route::get('/automations/create', [AdminAutomationController::class, 'create'])->name('automations.create');
    Route::get('/automations/{business_account}', [AdminAutomationController::class, 'show'])->name('automations.show');
    Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
});

require __DIR__.'/auth.php';
