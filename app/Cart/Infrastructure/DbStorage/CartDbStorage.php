<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DbStorage;

use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\CustomerId;
use App\Models\Cart as CartDbEntry;
use App\Models\Customer as CustomerDbEntry;

final class CartDbStorage implements CartStorage
{

    public function findByCartId(CartId $cartId): ?Cart
    {
        $dbEntry = CartDbEntry::find($cartId->value());

        if (null === $dbEntry) {
            return null;
        }

        return $this->toModel($dbEntry);
    }

    public function save(Cart $cart): void
    {
        $dbEntry = CartDbEntry::find($cart->getId()->value());

        if (null === $dbEntry) {
            $dbEntry = new CartDbEntry();
            $customer = CustomerDbEntry::find($cart->getCustomer()->getId()->value());
            $dbEntry->customer_id = $customer->id;
        }

        $dbEntry->id = $cart->getId()->value();
        $dbEntry->items = json_encode($cart->getProducts());
        $dbEntry->subtotal = $cart->getTotals()->getSubtotal();
        $dbEntry->total = $cart->getTotals()->getTotal();
        $dbEntry->discount = $cart->getTotals()->getDiscount();
        $dbEntry->save();
    }

    public function delete(Cart $cart): void
    {
        // TODO: Implement delete() method.
    }

    private function toModel(CartDbEntry $dbEntry): Cart
    {
        $items = [];
        $deserializedEntries = json_decode($dbEntry->items, true);

        foreach ($deserializedEntries as $item) {
            $items[] = Product::fromArray($item);
        }

        $customerDbEntry = CustomerDbEntry::find($dbEntry->customer_id);

        return new Cart(
            id: new CartId($dbEntry->id),
            customer: new Customer(
                id: new CustomerId($dbEntry->customer_id),
                name: $customerDbEntry->name,
                email: $customerDbEntry->email
            ),
            products: $items,
            totals: new Totals(
                subtotal: (float) $dbEntry->subtotal,
                total: (float) $dbEntry->total,
            )
        );
    }
}
