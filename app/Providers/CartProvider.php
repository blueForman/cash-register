<?php

namespace App\Providers;

use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\CustomerStorage;
use App\Cart\Domain\Storage\OrderStorage;
use App\Cart\Domain\Storage\ProductStorage;
use App\Cart\Infrastructure\DbStorage\CartDbStorage;
use App\Cart\Infrastructure\DbStorage\CustomerDbStorage;
use App\Cart\Infrastructure\DbStorage\OrderDbStorage;
use App\Cart\Infrastructure\DbStorage\ProductDbStorage;
use Illuminate\Support\ServiceProvider;

class CartProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CartStorage::class, function (): CartDbStorage {
            return new CartDbStorage();
        });

        $this->app->bind(CustomerStorage::class, function(): CustomerDbStorage {
            return new CustomerDbStorage();
        });

        $this->app->bind(ProductStorage::class, function(): ProductDbStorage {
            return new ProductDbStorage();
        });

        $this->app->bind(OrderStorage::class, function(): OrderDbStorage {
            return new OrderDbStorage();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
