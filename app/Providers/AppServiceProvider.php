<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\ItemRepository;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\SpecialPricingRuleRepository;
use App\Repositories\PricingRuleRepositoryInterface;
use App\Services\CheckoutService;
use App\Services\CheckoutServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(PricingRuleRepositoryInterface::class, SpecialPricingRuleRepository::class);
        $this->app->bind(CheckoutServiceInterface::class, CheckoutService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
