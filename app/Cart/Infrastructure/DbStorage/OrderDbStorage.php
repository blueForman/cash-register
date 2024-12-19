<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DbStorage;

use App\Cart\Domain\Model\Order;
use App\Cart\Domain\Storage\OrderStorage;
use App\Models\Order as OrderDbEntry;

final class OrderDbStorage implements OrderStorage
{

    public function save(Order $order): void
    {
        $orderEntry = new OrderDbEntry();
        $orderEntry->id = $order->getId();
        $orderEntry->customer_id = $order->getCustomer()->getId()->value();
        $orderEntry->items = json_encode($order->getItems());
        $orderEntry->subtotal = $order->getTotals()->getSubtotal();
        $orderEntry->total = $order->getTotals()->getTotal();
        $orderEntry->discount = $order->getTotals()->getDiscount();
        $orderEntry->save();
    }
}
