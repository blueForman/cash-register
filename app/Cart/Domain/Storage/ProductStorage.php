<?php

declare(strict_types=1);

namespace App\Cart\Domain\Storage;

use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Value\Sku;

interface ProductStorage
{
    public function findBySku(Sku $sku): ?Product;
}
