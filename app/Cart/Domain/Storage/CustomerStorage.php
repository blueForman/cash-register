<?php

namespace App\Cart\Domain\Storage;

use App\Cart\Domain\DTO\CustomerID;
use App\Cart\Domain\Model\Customer;

interface CustomerStorage
{
    public function find(CustomerId $id): ?Customer;
}
