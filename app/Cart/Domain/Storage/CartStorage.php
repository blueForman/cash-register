<?php

declare(strict_types=1);

namespace App\Cart\Domain\Storage;

use App\Cart\Domain\Model\Cart;
use App\Cart\Domain\Value\CartId;

interface CartStorage
{
    public function findByCartId(CartId $cartId): ?Cart;

    public function save(Cart $cart): void;
}
