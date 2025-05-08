<?php

namespace App\Providers;

use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Product\ProductRepository; // Cập nhật namespace
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
