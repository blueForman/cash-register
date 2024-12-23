<?php
declare(strict_types=1);
namespace Tests\Unit\Cart\Domain\Handler;


use App\Cart\Domain\Command\InitiateCartCommand;
use App\Cart\Domain\Command\InitiateCartHandler;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Exception\CustomerNotFoundException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Customer;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Storage\CustomerStorage;
use PHPUnit\Framework\TestCase;

final class InitiateCartHandlerTest extends TestCase
{
    public function testExceptionGetsThrownWhenCustomerDoesNotExist(): void
    {
        $missingCustomerId = 123;
        $expectedException = CustomerNotFoundException::withId($missingCustomerId);
        $this->expectExceptionObject($expectedException);
        $command = InitiateCartCommand::fromId($missingCustomerId);

        $customerStorage = $this->createMock(CustomerStorage::class);
        $customerStorage->method('findByCustomerId')->with($command->getCustomerId())->willReturn(null);
        $cartStorage = $this->createMock(CartStorage::class);
        $handler = new InitiateCartHandler($customerStorage, $cartStorage);

        $handler->handle($command);
    }

    public function testCartGetsInitiatedWhenCustomerExists(): void
    {
        $customerId = 123;
        $command = InitiateCartCommand::fromId($customerId);

        $customerStorage = $this->createMock(CustomerStorage::class);
        $customerStorage->method('findByCustomerId')->with($command->getCustomerId())->willReturn(new Customer($command->getCustomerId(), 'name', 'email'));

        $cartStorage = $this->createMock(CartStorage::class);
        $cartStorage->expects($this->once())->method('save');
        $handler = new InitiateCartHandler($customerStorage, $cartStorage);

        $cart = $handler->handle($command);
        self::assertInstanceOf(Cart::class, $cart);
        self::assertInstanceOf(Customer::class, $cart->getCustomer());
        self::assertSame($command->getCustomerId(), $cart->getCustomer()->getId());
        self::assertInstanceOf(Totals::class, $cart->getTotals());
    }
}
