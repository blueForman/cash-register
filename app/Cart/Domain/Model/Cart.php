<?php
declare(strict_types=1);
namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\CartId;

final class Cart
{
    /**
     * @param CartId $id
     * @param Customer $customer
     * @param Product[] $items
     * @param Totals|null $totals
     */
    public function __construct(private readonly CartId $id,
                                private readonly Customer $customer,
                                private array $items,
                                private ?Totals $totals
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

    public function addProduct(Product $product, int $quantity): void
    {
        if (isset($this->items[$product->getSku()->value()])) {
            $product = $this->items[$product->getSku()->value()];
        }
        $product->setQuantity($product->getQuantity() + $quantity);
        $this->items[$product->getSku()->value()] = $product;
        $this->recalculateTotals();
    }

    private function recalculateTotals(): void
    {
        $subTotal = 0;
        $total = 0;
        foreach ($this->items as $product) {
            $productPrice = $product->getPrice() * $product->getQuantity();
            $subTotal += $productPrice;
            $total += $productPrice * (1 - $product->getDiscount() / 100);
        }

        $this->totals = new Totals($subTotal, $total);
    }
}
