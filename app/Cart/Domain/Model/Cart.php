<?php
declare(strict_types=1);

namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\CartId;

final class Cart
{
    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @param CartId $id
     * @param Customer $customer
     * @param Item[] $items
     * @param Totals|null $totals
     */
    public function __construct(
        private readonly CartId   $id,
        private readonly Customer $customer,
        array                     $items,
        private ?Totals           $totals
    )
    {
        foreach ($items as $item) {
            $this->items[$item->getSku()->value()] = $item;
        }
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

    public function increaseProductQuantity(Product $product, int $quantity): void
    {
        if (isset($this->items[$product->getSku()->value()])) {
            $item = $this->items[$product->getSku()->value()];
            $item->setQuantity($item->getQuantity() + $quantity);
        } else {
            $item = new Item($product, $quantity);
        }

        $this->items[$product->getSku()->value()] = $item;

        $this->recalculateTotals();
    }

    public function decreaseProductQuantity(Product $product, ?int $quantity): void
    {
        if (isset($this->items[$product->getSku()->value()])) {
            $item = $this->items[$product->getSku()->value()];
        } else {
            return;
        }

        if (null === $quantity || $quantity >= $item->getQuantity()) {
            unset($this->items[$product->getSku()->value()]);
            return;
        }

        $item->setQuantity($item->getQuantity() - $quantity);
        $this->items[$product->getSku()->value()] = $item;
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        $this->removeEmptyProductsIfThereAreAny();
        $subTotal = 0;
        $total = 0;
        foreach ($this->items as $item) {
            $productPrice = $item->getProduct()->getPrice() * $item->getQuantity();
            $subTotal += $productPrice;
            $total += $productPrice * (1 - $item->getProduct()->getDiscount() / 100);
        }

        $this->totals = new Totals($subTotal, $total);
    }

    public function removeEmptyProductsIfThereAreAny(): void
    {
        $items = $this->items;
        foreach ($items as $item) {
            if ($item->getQuantity() < 1) {
                unset($this->items[$item->getSku()->value()]);
            }
        }
    }
}
