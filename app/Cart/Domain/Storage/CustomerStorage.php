<?php
declare(strict_types=1);
namespace App\Cart\Domain\Storage;

use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Model\Customer;

interface CustomerStorage
{
    public function findByCustomerId(CustomerId $id): ?Customer;
}
