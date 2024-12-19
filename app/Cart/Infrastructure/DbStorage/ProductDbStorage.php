<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DbStorage;

use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Storage\ProductStorage;
use App\Cart\Domain\Value\Sku;

final class ProductDbStorage implements ProductStorage
{

    public function findBySku(Sku $sku): ?Product
    {
        // TODO: Implement findBySku() method.
    }
}
