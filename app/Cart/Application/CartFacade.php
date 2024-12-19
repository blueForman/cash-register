<?php

declare(strict_types=1);

namespace App\Cart\Application;

use App\Cart\Application\ReadModel\CartReadModel;
use App\Cart\Application\ReadModel\CustomerReadModel;
use App\Cart\Application\ReadModel\ProductReadModel;
use App\Cart\Domain\Command\AddProductToCartCommand;
use App\Cart\Domain\Command\AddProductToCartHandler;
use App\Cart\Domain\Command\InitiateCartCommand;
use App\Cart\Domain\Command\InitiateCartHandler;
use App\Cart\Domain\Command\RemoveFromCartCommand;
use App\Cart\Domain\Command\RemoveFromCartHandler;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\CustomerId;
use App\Cart\Domain\Value\Sku;

final class CartFacade
{
    public function __construct(
        private readonly InitiateCartHandler $initiateCartHandler,
        private readonly AddProductToCartHandler $addProductToCartHandler,
        private readonly RemoveFromCartHandler $removeFromCartHandler,
    ) {
    }

    public function initiateCartForCustomer(int $customerId): CartReadModel
    {
        $command = InitiateCartCommand::fromId($customerId);

        $cart = $this->initiateCartHandler->handle($command);

        return $this->toCartReadModel($cart);
    }

    public function addProductToCart(string $cartId, string $sku, int $quantity): CartReadModel
    {
        $command = new AddProductToCartCommand(
            cartId: new CartId($cartId),
            sku: new Sku($sku),
            quantity: $quantity
        );

        $cart = $this->addProductToCartHandler->handle($command);

        return $this->toCartReadModel($cart);
    }

    public function removeFromCart(string $cartId, string $sku, int $quantity): CartReadModel
    {
        $command = new RemoveFromCartCommand(
            cartId: new CartId($cartId),
            sku: new Sku($sku),
            quantity: $quantity
        );

        $cart = $this->removeFromCartHandler->handle($command);

        return $this->toCartReadModel($cart);
    }

    private function toCartReadModel(Cart $cart): CartReadModel
    {
        $productReadModels = [];
        foreach ($cart->getProducts() as $item) {
            $productReadModels[] = new ProductReadModel(
                sku: $item->getSku()->value(),
                name: $item->getName(),
                price: $item->getPrice(),
                quantity: $item->getQuantity(),
                discount: $item->getDiscount(),
            );
        }

        $customerReadModel = new CustomerReadModel(
            id: $cart->getCustomer()->getId()->value(),
            name: $cart->getCustomer()->getName(),
            email: $cart->getCustomer()->getEmail(),
        );

        return new CartReadModel(
            id: $cart->getId()->value(),
            customer: $customerReadModel,
            products: $productReadModels,
            total: $cart->getTotals()->getTotal(),
            subtotal: $cart->getTotals()->getSubtotal(),
            discount: $cart->getTotals()->getDiscount()
        );
    }
}
