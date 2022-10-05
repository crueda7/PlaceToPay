<?php

namespace App\Providers;

use App\Interfaces\OrderRepository;
use App\Interfaces\ProductRepository;
use App\Interfaces\ShoppingCartRepository;
use App\Repositories\OrderRepositoryImpl;
use App\Repositories\ProductRepositoryImpl;
use App\Repositories\ShoppingCartRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(OrderRepository::class, OrderRepositoryImpl::class);
        $this->app->bind(ProductRepository::class, ProductRepositoryImpl::class);
        $this->app->bind(ShoppingCartRepository::class, ShoppingCartRepositoryImpl::class);
    }
}
