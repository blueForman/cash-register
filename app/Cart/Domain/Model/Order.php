<?php

declare(strict_types=1);

namespace App\Cart\Domain\Model;

use App\Cart\Domain\Enum\OrderStateEnum;
use App\Cart\Domain\Value\CartId;

final class Order
{
    /**
     * @param CartId $id
     * @param Customer $customer
     * @param Product[] $items
     * @param Totals|null $totals
     */
    public function __construct(
        private readonly Customer $customer,
        private array             $items,
        private Totals            $totals,
        private OrderStateEnum    $state,
    ) {
    }

    public function getId(): CartId
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotals(): ?Totals
    {
        return $this->totals;
    }

    public function getState(): OrderStateEnum
    {
        return $this->state;
    }
}
