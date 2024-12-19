<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DbStorage;

use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Storage\ProductStorage;
use App\Cart\Domain\Value\Sku;
use App\Models\Product as ProductDbModel;

final class ProductDbStorage implements ProductStorage
{

    public function findBySku(Sku $sku): ?Product
    {
        $productDbEntry = ProductDbModel::query()->where('sku', $sku->value())->first();
        if (null === $productDbEntry) {
            return null;
        }

        return $this->toDomainModel($productDbEntry);
    }

    private function toDomainModel(ProductDbModel $productDbEntry): Product
    {
        return new Product(
            sku: new Sku($productDbEntry->sku),
            name: $productDbEntry->name,
            price: (float)$productDbEntry->price,
            discount: (int) $productDbEntry->discount
        );
    }
}
