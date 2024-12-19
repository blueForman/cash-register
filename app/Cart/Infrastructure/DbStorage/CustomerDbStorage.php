<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DbStorage;

use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Storage\CustomerStorage;
use App\Cart\Domain\Value\CustomerId;
use App\Models\Customer as CustomerDbModel;

final class CustomerDbStorage implements CustomerStorage
{

    public function findByCustomerId(CustomerId $id): ?Customer
    {
        $customerDbEntry = CustomerDbModel::find($id->value());
        if ($customerDbEntry === null) {
            return null;
        }

        return $this->toDomainModel($customerDbEntry);
    }

    private function toDomainModel($customerDbEntry): Customer
    {
        return new Customer(
            id: new CustomerId($customerDbEntry->id),
            name: $customerDbEntry->name,
            email: $customerDbEntry->email,
        );
    }
}
