<?php

declare(strict_types=1);

namespace Tests\Unit\Cart\Domain\Model;

use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
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
            new Customer(new CustomerId(1)),
            [
                new Product(
                    new Sku('1'),
                    'foo',
                    3,
                    123,
                    0
                ),
                new Product(
                    new Sku('2'),
                    'bar',
                    0,
                    123,
                    0
                ),
                new Product(
                    new Sku('3'),
                    'baz',
                    -1,
                    123,
                    0
                )
            ],
            Totals::createEmpty()
        );

        $cart->removeEmptyProductsIfThereAreAny();

        self::assertCount(1, $cart->getItems());
    }
}
