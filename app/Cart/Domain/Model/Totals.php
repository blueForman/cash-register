<?php
declare(strict_types=1);
namespace App\Cart\Domain\Model;

final class Totals
{
    public function __construct(
        private readonly float $subtotal,
        private readonly float $total,
    ) {
    }

    public static function createEmpty(): self
    {
        return new self(0.0, 0.0);
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getDiscount(): float
    {
        return $this->subtotal - $this->total;
    }
}
