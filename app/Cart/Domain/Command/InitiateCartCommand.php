<?php

namespace App\Cart\Domain\Command;

use App\Cart\Domain\DTO\CustomerID;

final class InitiateCartCommand
{
    private function __construct(private readonly CustomerID $customerID)
    {
    }

    public function getCustomerId(): CustomerID
    {
        return $this->customerID;
    }

    public static function fromId(int $customerID): self
    {
        return new self(new CustomerID($customerID));
    }
}
