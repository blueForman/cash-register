<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DbStorage;

use App\Cart\Domain\Model\Order;
use App\Cart\Domain\Storage\OrderStorage;

final class OrderDbStorage implements OrderStorage
{

    public function save(Order $order): void
    {
        // TODO: Implement save() method.
    }
}
