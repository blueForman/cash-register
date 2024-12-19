<?php

declare(strict_types=1);

namespace App\Cart\Application;

use App\Cart\Application\ReadModel\CartReadModel;
use App\Cart\Application\ReadModel\CustomerReadModel;
use App\Cart\Application\ReadModel\ProductReadModel;
use App\Cart\Domain\Command\InitiateCartCommand;
use App\Cart\Domain\Command\InitiateCartHandler;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Value\CustomerId;

final class CartFacade
{
    public function __construct(
        private readonly InitiateCartHandler $initiateCartHandler,
    ) {
    }

    public function initiateCartForCustomer(int $customerId): CartReadModel
    {
        $command = InitiateCartCommand::fromId($customerId);

        $cart = $this->initiateCartHandler->handle($command);

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