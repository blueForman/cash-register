<?php

namespace App\Cart\Domain\Command;

use App\Cart\Domain\Exception\CustomerNotFoundException;
use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Model\Totals;
use App\Cart\Domain\Service\CartIdGenerator;
use App\Cart\Domain\Storage\CustomerStorage;

final class InitiateCartHandler
{
    public function __construct(private readonly CustomerStorage $customerStorage)
    {
    }

    public function handle(InitiateCartCommand $command): Cart
    {
        $customer = $this->customerStorage->find($command->getCustomerId());

        if (null === $customer) {
            throw CustomerNotFoundException::withId($command->getCustomerId()->value());
        }

        return new Cart(
            CartIdGenerator::generate(),
            $customer,
            [],
            Totals::createEmpty()
        );
    }
}
