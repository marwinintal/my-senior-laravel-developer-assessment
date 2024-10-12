<?php

namespace App\Providers;

use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::macro('softDeletes', function ($uri, $controller) {
            Route::get("{$uri}/soft-deleted", "{$controller}@softDeleted")->name("{$uri}.softDeleted");
            Route::patch("{$uri}/{id}/restore", "{$controller}@restore")->name("{$uri}.restore");
            Route::delete("{$uri}/{id}/force-delete", "{$controller}@forceDelete")->name("{$uri}.forceDelete");
        });
    }
}
