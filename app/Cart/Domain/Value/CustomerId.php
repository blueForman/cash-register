<?php

namespace App\Cart\Domain\Value;

final class CustomerId
{
    public function __construct(private readonly int $value)
    {
    }

    public function value(): int
    {
        return $this->value;
    }
}
