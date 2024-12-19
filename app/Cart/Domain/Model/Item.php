<?php

declare(strict_types=1);

namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\Sku;

final class Item implements \JsonSerializable
{
    public function __construct(
        private readonly Product $product,
        private int $quantity
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getSku(): Sku
    {
        return $this->product->getSku();
    }

    public function jsonSerialize(): array
    {
        return [
            'product' => $this->product,
            'quantity' => $this->quantity,
        ];
    }
}
