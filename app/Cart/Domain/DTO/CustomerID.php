<?php

namespace App\Cart\Domain\DTO;

final class CustomerID
{
    public function __construct(private readonly int $id)
    {
    }

    public function value(): int
    {
        return $this->id;
    }
}
