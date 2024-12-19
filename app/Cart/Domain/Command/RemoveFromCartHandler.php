<?php

declare(strict_types=1);

namespace App\Cart\Domain\Command;

use App\Cart\Domain\Exception\CartNotFoundException;
use App\Cart\Domain\Exception\InvalidQuantityException;
use App\Cart\Domain\Exception\ProductNotFoundException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\ProductStorage;

final class RemoveFromCartHandler
{
    public function __construct(
        private readonly CartStorage $cartStorage,
        private readonly ProductStorage $productStorage,
    ) {
    }

    public function handle(RemoveFromCartCommand $command): Cart
    {
        if ($command->getQuantity() < 1) {
            throw InvalidQuantityException::becauseAddQuantityIsZeroOrLower();
        }

        $cart = $this->cartStorage->findByCartId($command->getCartId());

        if (null === $cart) {
            throw CartNotFoundException::byCartId($command->getCartId()->value());
        }

        $product = $this->productStorage->findBySku($command->getSku());

        if (null === $product) {
            throw ProductNotFoundException::bySku($command->getSku()->value());
        }

        $cart->decreaseProductQuantity($product, $command->getQuantity());

        $this->cartStorage->save($cart);
        return $cart;
    }
}
