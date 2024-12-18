<?php

declare(strict_types=1);

namespace App\Cart\Domain\Command;

use App\Cart\Domain\Value\CartId;

final class CreateOrderCommand
{
    public function __construct(private readonly CartId $cartId)
    {
    }

    public function getCartId(): CartId
    {
        return $this->cartId;
    }
}
