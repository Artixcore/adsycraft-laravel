<?php

use App\Http\Controllers\Api\AdLibraryController;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\BrandVoiceController;
use App\Http\Controllers\Api\BusinessAccountController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\MetaAdsController;
use App\Http\Controllers\Api\MetaConnectorController;
use App\Http\Controllers\Api\PageInsightController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ResearchController;
use App\Http\Controllers\Api\UserMetadataController;
use App\Http\Controllers\Api\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('workspaces', WorkspaceController::class)->parameters(['workspaces' => 'workspace']);
    Route::get('workspaces/{workspace}/brand-voices', [BrandVoiceController::class, 'index'])->name('workspaces.brand-voices.index');
    Route::post('workspaces/{workspace}/brand-voices', [BrandVoiceController::class, 'store'])->name('workspaces.brand-voices.store');
    Route::get('workspaces/{workspace}/brand-voices/{brand_voice}', [BrandVoiceController::class, 'show'])->name('workspaces.brand-voices.show');
    Route::put('workspaces/{workspace}/brand-voices/{brand_voice}', [BrandVoiceController::class, 'update'])->name('workspaces.brand-voices.update');
    Route::delete('workspaces/{workspace}/brand-voices/{brand_voice}', [BrandVoiceController::class, 'destroy'])->name('workspaces.brand-voices.destroy');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::apiResource('user-metadata', UserMetadataController::class)->parameters(['user-metadata' => 'metadata']);
    Route::apiResource('businesses', BusinessAccountController::class)->parameters(['businesses' => 'business']);
    Route::post('businesses/{business}/toggle-autopilot', [BusinessAccountController::class, 'toggleAutopilot'])
        ->name('businesses.toggle-autopilot');
    Route::post('businesses/{business}/generate-today', [BusinessAccountController::class, 'generateToday'])
        ->name('businesses.generate-today');
    Route::get('businesses/{business}/posts', [PostController::class, 'index'])->name('businesses.posts');
    Route::post('businesses/{business}/posts', [PostController::class, 'store'])->name('businesses.posts.store');
    Route::get('businesses/{business}/posts/{post}', [PostController::class, 'show'])->name('businesses.posts.show');
    Route::put('businesses/{business}/posts/{post}', [PostController::class, 'update'])->name('businesses.posts.update');
    Route::delete('businesses/{business}/posts/{post}', [PostController::class, 'destroy'])->name('businesses.posts.destroy');
    Route::post('businesses/{business}/posts/{post}/schedule', [PostController::class, 'schedule'])->name('businesses.posts.schedule');
    Route::post('businesses/{business}/posts/{post}/publish', [PostController::class, 'publishNow'])->name('businesses.posts.publish');
    Route::get('businesses/{business}/posts/{post}/metrics', [PostController::class, 'metrics'])->name('businesses.posts.metrics');
    Route::post('businesses/{business}/posts/{post}/regenerate', [PostController::class, 'regenerate'])->name('businesses.posts.regenerate');
    Route::get('businesses/{business}/calendar', [CalendarController::class, 'index'])->name('businesses.calendar');
    Route::get('businesses/{business}/calendar/day', [CalendarController::class, 'day'])->name('businesses.calendar.day');
    Route::post('businesses/{business}/calendar/generate-day', [CalendarController::class, 'generateDay'])->name('businesses.calendar.generate-day');
    Route::get('businesses/{business}/insights', [PageInsightController::class, 'index'])->name('businesses.insights');
    Route::post('businesses/{business}/research/trigger', [ResearchController::class, 'trigger'])->name('businesses.research.trigger');
    Route::get('businesses/{business}/research/results', [ResearchController::class, 'results'])->name('businesses.research.results');
    Route::get('businesses/{business}/inbox/conversations', [InboxController::class, 'conversations'])->name('businesses.inbox.conversations');
    Route::get('businesses/{business}/inbox/conversations/{conversation}/messages', [InboxController::class, 'messages'])->name('businesses.inbox.messages');
    Route::post('businesses/{business}/inbox/conversations/{conversation}/reply', [InboxController::class, 'reply'])->name('businesses.inbox.reply');
    Route::get('ads/ad-accounts', [AdsController::class, 'adAccounts'])->name('ads.ad-accounts');
    Route::get('ads/ad-accounts/{ad_account}/campaigns', [AdsController::class, 'campaigns'])->name('ads.campaigns');

    Route::get('businesses/{business}/connectors/meta/status', [MetaConnectorController::class, 'status'])->name('businesses.connectors.meta.status');
    Route::post('businesses/{business}/connectors/meta/auth-url', [MetaConnectorController::class, 'authUrl'])->name('businesses.connectors.meta.auth-url');
    Route::get('businesses/{business}/connectors/meta/assets', [MetaConnectorController::class, 'assets'])->name('businesses.connectors.meta.assets');
    Route::post('businesses/{business}/connectors/meta/assets/select', [MetaConnectorController::class, 'selectAssets'])->name('businesses.connectors.meta.assets.select');
    Route::post('businesses/{business}/connectors/meta/disconnect', [MetaConnectorController::class, 'disconnect'])->name('businesses.connectors.meta.disconnect');
    Route::get('businesses/{business}/connectors/meta/debug', [MetaConnectorController::class, 'debug'])->name('businesses.connectors.meta.debug');

    Route::get('businesses/{business}/ads/accounts', [MetaAdsController::class, 'adAccounts'])->name('businesses.ads.accounts');
    Route::post('businesses/{business}/ads/accounts/select', [MetaAdsController::class, 'selectAdAccount'])->name('businesses.ads.accounts.select');

    Route::prefix('ad-library')->group(function () {
        Route::get('config', [AdLibraryController::class, 'config'])->name('ad-library.config');
        Route::post('search', [AdLibraryController::class, 'search'])->name('ad-library.search');
        Route::get('searches', [AdLibraryController::class, 'searchesIndex'])->name('ad-library.searches.index');
        Route::post('searches', [AdLibraryController::class, 'searchesStore'])->name('ad-library.searches.store');
        Route::delete('searches/{id}', [AdLibraryController::class, 'searchesDestroy'])->name('ad-library.searches.destroy');
        Route::get('collections', [AdLibraryController::class, 'collectionsIndex'])->name('ad-library.collections.index');
        Route::post('collections', [AdLibraryController::class, 'collectionsStore'])->name('ad-library.collections.store');
        Route::get('collections/{collection}/items', [AdLibraryController::class, 'collectionsShowItems'])->name('ad-library.collections.items');
        Route::post('collections/{collection}/items', [AdLibraryController::class, 'collectionsAddItem'])->name('ad-library.collections.items.store');
        Route::delete('collections/{collection}/items/{item}', [AdLibraryController::class, 'collectionsRemoveItem'])->name('ad-library.collections.items.destroy');
    });
});
