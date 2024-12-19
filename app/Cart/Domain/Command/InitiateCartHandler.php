<?php
declare(strict_types=1);
namespace App\Cart\Domain\Command;

use App\Cart\Domain\Exception\CustomerNotFoundException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Service\CartIdGenerator;
use App\Cart\Domain\Storage\CartStorage;
use App\Cart\Domain\Storage\CustomerStorage;

final class InitiateCartHandler
{
    public function __construct(
        private readonly CustomerStorage $customerStorage,
        private readonly CartStorage $cartStorage,
    ) {
    }

    public function handle(InitiateCartCommand $command): Cart
    {
        $customer = $this->customerStorage->findByCustomerId($command->getCustomerId());

        if (null === $customer) {
            throw CustomerNotFoundException::withId($command->getCustomerId()->value());
        }

        $cart = new Cart(
            CartIdGenerator::generate(),
            $customer,
            [],
            Totals::createEmpty()
        );

        $this->cartStorage->save($cart);
        return $cart;

    }
}
