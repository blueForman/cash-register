<?php

namespace App\Cart\Domain\Model;

final class Cart
{
    public function __construct(private readonly string $id, private readonly Customer $customer, private array $items, private ?Totals $totals = new Totals())
    {
    }

    public function getId(): string
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
}
