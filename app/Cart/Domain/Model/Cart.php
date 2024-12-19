<?php
declare(strict_types=1);

namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\CartId;

final class Cart
{
    /**
     * @var Product[]
     */
    private array $products = [];

    /**
     * @param CartId $id
     * @param Customer $customer
     * @param Product[] $products
     * @param Totals|null $totals
     */
    public function __construct(
        private readonly CartId   $id,
        private readonly Customer $customer,
        array                     $products,
        private ?Totals           $totals
    )
    {
        foreach ($products as $product) {
            $this->products[$product->getSku()->value()] = $product;
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

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getTotals(): ?Totals
    {
        return $this->totals;
    }

    public function increaseProductQuantity(Product $product, int $quantity): void
    {
        if (isset($this->products[$product->getSku()->value()])) {
            $product = $this->products[$product->getSku()->value()];
        }
        $product->setQuantity($product->getQuantity() + $quantity);
        $this->products[$product->getSku()->value()] = $product;
        $this->recalculateTotals();
    }

    public function decreaseProductQuantity(Product $product, ?int $quantity): void
    {
        if (isset($this->products[$product->getSku()->value()])) {
            $product = $this->products[$product->getSku()->value()];
        }

        if (null === $quantity || $quantity >= $product->getQuantity()) {
            unset($this->products[$product->getSku()->value()]);
            return;
        }

        $product->setQuantity($product->getQuantity() - $quantity);
        $this->products[$product->getSku()->value()] = $product;
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        $this->removeEmptyProductsIfThereAreAny();
        $subTotal = 0;
        $total = 0;
        foreach ($this->products as $product) {
            $productPrice = $product->getPrice() * $product->getQuantity();
            $subTotal += $productPrice;
            $total += $productPrice * (1 - $product->getDiscount() / 100);
        }

        $this->totals = new Totals($subTotal, $total);
    }

    public function removeEmptyProductsIfThereAreAny(): void
    {
        $products = $this->products;
        foreach ($products as $product) {
            if ($product->getQuantity() < 1) {
                unset($this->products[$product->getSku()->value()]);
            }
        }
    }
}
