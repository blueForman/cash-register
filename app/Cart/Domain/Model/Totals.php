<?php

namespace App\Cart\Domain\Model;

final class Totals
{
    public function __construct(
        private readonly float $subtotal,
        private readonly float $tax,
        private readonly float $total,
        private readonly float $discount
    ) {
    }

    public static function createEmpty(): self
    {
        return new self(0.0, 0.0, 0.0, 0.0);
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }
}
