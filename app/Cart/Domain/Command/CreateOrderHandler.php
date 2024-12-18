<?php

declare(strict_types=1);

namespace App\Cart\Domain\Command;

use App\Cart\Domain\Enum\OrderStateEnum;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Cart\Domain\Exception\OrderCreationException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Order;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\OrderStorage;

final class CreateOrderHandler
{
    public function __construct(
        private readonly CartStorage $cartStorage,
        private readonly OrderStorage $orderStorage,
    )
    {
    }

    public function handle(CreateOrderCommand $command): void
    {
        $cart = $this->cartStorage->findByCartId($command->getCartId());

        if (null === $cart) {
            throw CartNotFoundException::byCartId($command->getCartId()->value());
        }

        if ($this->isCartEmpty($cart)) {
            throw OrderCreationException::emptyCart();
        }

        $order = new Order(
            $cart->getCustomer(),
            $cart->getItems(),
            $cart->getTotals(),
            OrderStateEnum::NEW
        );
        $this->orderStorage->save($order);
        $this->cartStorage->delete($cart);
    }

    private function isCartEmpty(Cart $cart): bool
    {
        if (empty($cart->getItems())) {
            return true;
        }

        return false;
    }
}