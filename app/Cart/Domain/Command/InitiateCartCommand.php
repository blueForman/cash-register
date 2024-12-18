<?php
declare(strict_types=1);
namespace App\Cart\Domain\Command;

use App\Cart\Domain\Value\CustomerId;

final class InitiateCartCommand
{
    private function __construct(private readonly CustomerId $customerID)
    {
    }

    public function getCustomerId(): CustomerId
    {
        return $this->customerID;
    }

    public static function fromId(int $customerID): self
    {
        return new self(new CustomerId($customerID));
    }
}
