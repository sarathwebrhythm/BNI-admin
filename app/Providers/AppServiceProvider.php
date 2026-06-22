<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // ADD THIS
use App\Repositories\Contracts\MemberRepositoryInterface;
use App\Repositories\Eloquent\MemberRepository;
use App\Repositories\Contracts\UploadHistoryRepositoryInterface;
use App\Repositories\Eloquent\UploadHistoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(UploadHistoryRepositoryInterface::class, UploadHistoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}