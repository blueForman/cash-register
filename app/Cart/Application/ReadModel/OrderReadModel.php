<?php

declare(strict_types=1);

namespace App\Cart\Application\ReadModel;

final class OrderReadModel implements \JsonSerializable
{
    public
    function __construct(
        public readonly string            $id,
        public readonly CustomerReadModel $customer,
        public readonly array             $items,
        public readonly float             $total,
        public readonly float             $subtotal,
        public readonly float             $discount
    ) {
    }

    public
    function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'items' => $this->items,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
        ];
    }
}
