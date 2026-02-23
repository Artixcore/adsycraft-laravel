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
    return view('home');
})->name('home');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

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
    Route::get('/onboarding', function () {
        return view('onboarding.index');
    })->name('onboarding');

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/dashboard/connectors', function () {
        return view('dashboard.connectors', [
            'ai_configured' => app(\App\Services\AI\AIManager::class)->hasConfiguredProvider(),
        ]);
    })->name('dashboard.connectors');

    Route::get('/dashboard/ad-library', function () {
        return view('dashboard.ad-library');
    })->name('dashboard.ad-library');

    Route::get('/dashboard/ads', function () {
        return view('dashboard.ads');
    })->name('dashboard.ads');

    Route::get('/dashboard/calendar', function () {
        return view('dashboard.calendar');
    })->name('dashboard.calendar');

    Route::get('/dashboard/growth-blueprint', function () {
        return view('dashboard.growth-blueprint');
    })->name('dashboard.growth-blueprint');
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
