<?php

declare(strict_types=1);

namespace Tests\Unit\Cart\Domain\Handler;

use App\Cart\Domain\Command\DecreaseProductQuantityCommand;
use App\Cart\Domain\Command\DecreaseProductQuantityHandler;
use App\Cart\Domain\Command\IncreaseProductQuantityCommand;
use App\Cart\Domain\Command\IncreaseProductQuantityHandler;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Cart\Domain\Exception\InvalidQuantityException;
use App\Cart\Domain\Exception\ProductNotFoundException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Service\CartIdGenerator;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\ProductStorage;
use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Value\Sku;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Test;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

final class DecreaseProductQuantityHandlerTest extends TestCase
{

    public function testExceptionIsThrownWhenCartDoesNotExist(): void
    {
        $missingCartId = Uuid::uuid4()->toString();
        $cartId = new CartId($missingCartId);
        $expectedException = CartNotFoundException::byCartId($missingCartId);
        $this->expectExceptionObject($expectedException);

        $command = new DecreaseProductQuantityCommand($cartId, new Sku('foobar'), 1);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage->method('findByCartId')->with($cartId)->willReturn(null);
        $productStorage = $this->createMock(ProductStorage::class);
        $addProductToCartHandler = new DecreaseProductQuantityHandler($cartStorage, $productStorage);

        $addProductToCartHandler->handle($command);
    }

    public function testExceptionIsThrownWhenProductIsNotFound(): void
    {
        $cartId = CartIdGenerator::generate();
        $nonExistantProductSku = new Sku('foobar');
        $expectedException = ProductNotFoundException::bySku($nonExistantProductSku->value());
        $this->expectExceptionObject($expectedException);

        $command = new DecreaseProductQuantityCommand($cartId, $nonExistantProductSku, 1);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123)),
                    [],
                    Totals::createEmpty()
                )
            );

        $productStorage = $this->createMock(ProductStorage::class);
        $productStorage->method('findBySku')->with($nonExistantProductSku)->willReturn(null);
        $addProductToCartHandler = new DecreaseProductQuantityHandler($cartStorage, $productStorage);
        $addProductToCartHandler->handle($command);
    }

    #[DataProvider('quantityProvider')]
    public function testExceptionIsThrownWhenQuantityIsLowerThanZero(int $quatity): void
    {
        $expectedException = InvalidQuantityException::becauseAddQuantityIsZeroOrLower();
        $this->expectExceptionObject($expectedException);
        $cartId = CartIdGenerator::generate();
        $productSku = new Sku('foobar');

        $command = new DecreaseProductQuantityCommand($cartId, $productSku, $quatity);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123)),
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
        $addProductToCartHandler = new DecreaseProductQuantityHandler($cartStorage, $productStorage);
        $addProductToCartHandler->handle($command);
    }

    public function testQuantityAndTotalsGetCalculatedCorrectly(): void
    {
        $cartId = CartIdGenerator::generate();
        $productSku = new Sku('foobar');

        $command = new DecreaseProductQuantityCommand($cartId, $productSku, 3);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123)),
                    [],
                    Totals::createEmpty()
                )
            );

        $product = new Product(
            $productSku,
            'some product',
            4,
            2.0,
            25
        );

        $productStorage = $this->createMock(ProductStorage::class);
        $productStorage->method('findBySku')->with($productSku)->willReturn($product);
        $addProductToCartHandler = new DecreaseProductQuantityHandler($cartStorage, $productStorage);
        $resultingCart = $addProductToCartHandler->handle($command);

        $resultingCartProducts = $resultingCart->getItems();
        self::assertNotNull($resultingCartProducts[$productSku->value()]);
        $productInCart = $resultingCartProducts[$productSku->value()];
        self::assertEquals(1, $productInCart->getQuantity());

        $totals = $resultingCart->getTotals();
        self::assertSame(2.0, $totals->getSubtotal());
        self::assertSame(1.5, $totals->getTotal());
        self::assertSame(0.5, $totals->getDiscount());
    }

    #[DataProvider('quantityToDecreaseProvider')]
    public function testItemGetsRemovedWhenDecreasedQuantityIsGreaterOrEqualTheQuantityInCart(int $quantityInCart, int $quantityToDecrease): void
    {

        $cartId = CartIdGenerator::generate();
        $productSku = new Sku('foobar');

        $command = new DecreaseProductQuantityCommand($cartId, $productSku, $quantityToDecrease);
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage
            ->method('findByCartId')
            ->with($cartId)
            ->willReturn(
                new Cart(
                    $cartId,
                    new Customer(new CustomerId(123)),
                    [],
                    Totals::createEmpty()
                )
            );

        $product = new Product(
            $productSku,
            'some product',
            $quantityInCart,
            2.0,
            25
        );

        $productStorage = $this->createMock(ProductStorage::class);
        $productStorage->method('findBySku')->with($productSku)->willReturn($product);
        $addProductToCartHandler = new DecreaseProductQuantityHandler($cartStorage, $productStorage);
        $resultingCart = $addProductToCartHandler->handle($command);

        $resultingCartProducts = $resultingCart->getItems();
        self::assertArrayNotHasKey($productSku->value(), $resultingCartProducts);

        $totals = $resultingCart->getTotals();
        self::assertSame(0.0, $totals->getSubtotal());
        self::assertSame(0.0, $totals->getTotal());
        self::assertSame(0.0, $totals->getDiscount());
    }


    public static function quantityProvider(): \Traversable
    {
        yield [0];
        yield [-1];
    }

    public static function quantityToDecreaseProvider(): \Traversable
    {
        yield 'Quantity to decrease is equal to quantity in cart' => [4, 4];
        yield 'Quantity to decrease is greater than quantity in cart' => [2, 3];
    }
}