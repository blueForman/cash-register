<?php

declare(strict_types=1);

namespace Tests\Unit\Cart\Domain\Handler;

use App\Cart\Domain\Command\AddProductToCartCommand;
use App\Cart\Domain\Command\AddProductToCartHandler;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Cart\Domain\Exception\InvalidQuantityException;
use App\Cart\Domain\Exception\ProductNotFoundException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Service\IdGenerator;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\ProductStorage;
use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Value\Sku;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class AddProductToCartHandlerTest extends TestCase
{
    public function testExceptionIsThrownWhenCartDoesNotExist(): void
    {
        $missingCartId = Uuid::uuid4()->toString();
        $cartId = new CartId($missingCartId);
        $expectedException = CartNotFoundException::byCartId($missingCartId);
        $this->expectExceptionObject($expectedException);

        $command = new AddProductToCartCommand($cartId, new Sku('foobar'), 1);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage->method('findByCartId')->with($cartId)->willReturn(null);
        $productStorage = $this->createMock(ProductStorage::class);
        $addProductToCartHandler = new AddProductToCartHandler($cartStorage, $productStorage);

        $addProductToCartHandler->handle($command);
    }

    public function testExceptionIsThrownWhenProductIsNotFound(): void
    {
        $cartId = IdGenerator::generateCartId();
        $nonExistentProductSku = new Sku('foobar');
        $expectedException = ProductNotFoundException::bySku($nonExistentProductSku->value());
        $this->expectExceptionObject($expectedException);

        $command = new AddProductToCartCommand($cartId, $nonExistentProductSku, 1);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123), 'name', 'email'),
                    [],
                    Totals::createEmpty()
                )
            );

        $productStorage = $this->createMock(ProductStorage::class);
        $productStorage->method('findBySku')->with($nonExistentProductSku)->willReturn(null);
        $addProductToCartHandler = new AddProductToCartHandler($cartStorage, $productStorage);
        $addProductToCartHandler->handle($command);
    }



    #[DataProvider('quantityProvider')]
    public function testExceptionIsThrownWhenQuantityIsLowerThanZero(int $quatity): void
    {
        $expectedException = InvalidQuantityException::becauseAddQuantityIsZeroOrLower();
        $this->expectExceptionObject($expectedException);
        $cartId = IdGenerator::generateCartId();
        $productSku = new Sku('foobar');

        $command = new AddProductToCartCommand($cartId, $productSku, $quatity);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123), 'name', 'email'),
                    [],
                    Totals::createEmpty()
                )
            );

        $product = new Product(
            $productSku,
            'some product',
            0,
            0.0,
            0
        );

        $productStorage = $this->createMock(ProductStorage::class);
        $productStorage->method('findBySku')->with($productSku)->willReturn($product);
        $addProductToCartHandler = new AddProductToCartHandler($cartStorage, $productStorage);
        $addProductToCartHandler->handle($command);
    }

    public function testQuantityAndTotalsGetCalculatedCorrectly(): void
    {
        $cartId = IdGenerator::generateCartId();
        $productSku = new Sku('foobar');

        $command = new AddProductToCartCommand($cartId, $productSku, 3);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123), 'name', 'email'),
                    [],
                    Totals::createEmpty()
                )
            );

        $product = new Product(
            $productSku,
            'some product',
            0,
            2.0,
            25
        );

        $productStorage = $this->createMock(ProductStorage::class);
        $productStorage->method('findBySku')->with($productSku)->willReturn($product);
        $addProductToCartHandler = new AddProductToCartHandler($cartStorage, $productStorage);
        $resultingCart = $addProductToCartHandler->handle($command);

        $resultingCartProducts = $resultingCart->getProducts();
        self::assertNotNull($resultingCartProducts[$productSku->value()]);
        $productInCart = $resultingCartProducts[$productSku->value()];
        self::assertEquals(3, $productInCart->getQuantity());

        $totals = $resultingCart->getTotals();
        self::assertSame(6.0, $totals->getSubtotal());
        self::assertSame(4.5, $totals->getTotal());
        self::assertSame(1.5, $totals->getDiscount());
    }

    public static function quantityProvider(): \Generator
    {
        yield [0];
        yield [-1];
    }
}
