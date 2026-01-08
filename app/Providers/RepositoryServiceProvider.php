<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repository Interfaces
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use App\Repositories\Interfaces\RequestRepositoryInterface;

// Repository Implementations
use App\Repositories\UserRepository;
use App\Repositories\PackageRepository;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CouponRepository;
use App\Repositories\RequestRepository;

// Service Interfaces
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Interfaces\PackageServiceInterface;
use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Interfaces\CouponServiceInterface;
use App\Services\Interfaces\RequestServiceInterface;

// Service Implementations
use App\Services\UserService;
use App\Services\PackageService;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\CouponService;
use App\Services\RequestService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repository Bindings
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(RequestRepositoryInterface::class, RequestRepository::class);

        // Service Bindings
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(PackageServiceInterface::class, PackageService::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(CouponServiceInterface::class, CouponService::class);
        $this->app->bind(RequestServiceInterface::class, RequestService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
