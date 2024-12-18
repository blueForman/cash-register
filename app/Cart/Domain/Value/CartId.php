<?php
declare(strict_types=1);
namespace App\Cart\Domain\Value;

final class CartId
{
    public function __construct(private readonly string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}
