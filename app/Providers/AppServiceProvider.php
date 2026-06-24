<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // ADD THIS
use App\Repositories\Contracts\MemberRepositoryInterface;
use App\Repositories\Eloquent\MemberRepository;
use App\Repositories\Contracts\UploadHistoryRepositoryInterface;
use App\Repositories\Eloquent\UploadHistoryRepository;
use App\Repositories\Contracts\OfferCategoryRepositoryInterface;
use App\Repositories\Eloquent\OfferCategoryRepository;
use App\Repositories\Contracts\PackageRepositoryInterface;
use App\Repositories\Eloquent\PackageRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            MemberRepositoryInterface::class,
            MemberRepository::class
        );

        $this->app->bind(
            UploadHistoryRepositoryInterface::class,
            UploadHistoryRepository::class
        );

        $this->app->bind(
            OfferCategoryRepositoryInterface::class,
            OfferCategoryRepository::class
        );
        $this->app->bind(
        PackageRepositoryInterface::class,
        PackageRepository::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
