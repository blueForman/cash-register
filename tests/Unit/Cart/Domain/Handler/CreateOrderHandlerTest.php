<?php

declare(strict_types=1);

namespace Tests\Unit\Cart\Domain\Handler;

use App\Cart\Domain\Command\CreateOrderCommand;
use App\Cart\Domain\Command\CreateOrderHandler;
use App\Cart\Domain\Enum\OrderStateEnum;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Cart\Domain\Exception\OrderCreationException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Model\Order;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Service\IdGenerator;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\OrderStorage;
use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Value\Sku;
use PHPUnit\Framework\TestCase;

final class CreateOrderHandlerTest extends TestCase
{
    public function testExceptionIsThrownWhenCartIsNotFound(): void
    {
        $nonExistentCartId = new CartId('foobar');
        $expectedException = CartNotFoundException::byCartId($nonExistentCartId->value());
        $this->expectExceptionObject($expectedException);
        $command = new CreateOrderCommand($nonExistentCartId);

        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage->method('findByCartId')->with($nonExistentCartId)->willReturn(null);

        $orderStorage = $this->createMock(OrderStorage::class);

        $handler = new CreateOrderHandler($cartStorage, $orderStorage);
        $handler->handle($command);
    }

    public function testExceptionGetsThrownWhenCartIsEmpty(): void
    {
        $cartId = IdGenerator::generateCartId();
        $command = new CreateOrderCommand($cartId);
        $expectedException = OrderCreationException::emptyCart();
        $this->expectExceptionObject($expectedException);

        $cart = new Cart(
            $cartId,
            new Customer(new CustomerId(123), 'name', 'email'),
            [
            ],
            Totals::createEmpty()
        );
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage->method('findByCartId')->with($cartId)->willReturn($cart);

        $orderStorage = $this->createMock(OrderStorage::class);

        $handler = new CreateOrderHandler($cartStorage, $orderStorage);
        $handler->handle($command);
    }

    public function testOrderGetsCreatedWhenCartIsNotEmpty(): void
    {
        $cartId = IdGenerator::generateCartId();
        $command = new CreateOrderCommand($cartId);

        $cart = new Cart(
            $cartId,
            new Customer(new CustomerId(123), 'name', 'email'),
            [
                new Product(
                    new Sku('foo'),
                    'bar',
                    1.0,
                    0
                )
            ],
            Totals::createEmpty()
        );
        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage->method('findByCartId')->with($cartId)->willReturn($cart);
        $cartStorage->expects($this->once())->method('delete')->with($cart);

        $expectedOrder = new Order(
            IdGenerator::generateUniqueId(),
            $cart->getCustomer(),
            $cart->getItems(),
            $cart->getTotals(),
            OrderStateEnum::NEW
        );

        $orderStorage = $this->createMock(OrderStorage::class);
        $orderStorage
            ->expects($this->once())
            ->method('save')
        ;

        $handler = new CreateOrderHandler($cartStorage, $orderStorage);
        $handler->handle($command);
    }
}
