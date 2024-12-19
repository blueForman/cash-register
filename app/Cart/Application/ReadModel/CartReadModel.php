<?php

declare(strict_types=1);

namespace App\Cart\Application\ReadModel;

final class CartReadModel implements \JsonSerializable
{
    public function __construct(
        public readonly string            $id,
        public readonly CustomerReadModel $customer,
        public readonly array             $products,
        public readonly float             $total,
        public readonly float            $subtotal,
        public readonly float            $discount
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'products' => $this->products,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
        ];
    }
}
