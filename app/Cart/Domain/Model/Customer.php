<?php

namespace App\Cart\Domain\Model;

use App\Cart\Domain\DTO\CustomerID;

final class Customer
{
    public function __construct(private readonly CustomerID $id)
    {
    }

    public function getId(): CustomerID
    {
        return $this->id;
    }
}
