<?php

declare(strict_types=1);

namespace Tests\Unit\Cart\Domain\Model;

use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Model\Item;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Value\Sku;
use PHPUnit\Framework\TestCase;

final class CartModelTest extends TestCase
{
    public function testItemsWithZeroQuantityOrLessGetRemovedFromItems(): void
    {
        $cart = new Cart(
            new CartId('123'),
            new Customer(new CustomerId(1), 'name', 'email'),
            [
                new Item(
                    new Product(
                        new Sku('1'),
                        'foo',
                        123,
                        0
                    ),
                    3
                ),
                new Item(
                    new Product(
                        new Sku('2'),
                        'bar',
                        123,
                        0
                    ),
                    0
                ),
                new Item(
                    new Product(
                        new Sku('3'),
                        'baz',
                        123,
                        0
                    ),
                    -1
                ),
            ],
            Totals::createEmpty()
        );

        $cart->removeEmptyProductsIfThereAreAny();

        self::assertCount(1, $cart->getItems());
    }
}
