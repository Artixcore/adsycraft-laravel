<?php

namespace App\Providers;

use App\Models\BusinessAccount;
use App\Models\Post;
use App\Observers\PostObserver;
use App\Services\AI\AIManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AIManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);
        Route::bind('business', fn (string $value) => BusinessAccount::findOrFail($value));
    }
}
