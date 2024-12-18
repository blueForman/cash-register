<?php

declare(strict_types=1);

namespace App\Cart\Domain\Storage;

use App\Cart\Domain\Model\Order;

interface OrderStorage
{
    public function save(Order $order): void;
}
