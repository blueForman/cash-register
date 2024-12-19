<?php

declare(strict_types=1);

namespace App\Cart\Application\ReadModel;

final class ProductReadModel implements \JsonSerializable
{
    public function __construct(
        private readonly string $sku,
        private readonly string $name,
        private readonly float $price,
        private readonly int $quantity,
        private readonly int $discount
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
        ];
    }
}
