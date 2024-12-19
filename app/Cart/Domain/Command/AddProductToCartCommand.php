<?php
declare(strict_types=1);
namespace App\Cart\Domain\Command;

use App\Cart\Domain\Value\CartId;
use App\Cart\Domain\Value\Sku;

final class AddProductToCartCommand
{
    public function __construct(
        private readonly CartId $cartId,
        private readonly Sku    $sku,
        private readonly int  $quantity
    ) {
    }

    public function getCartId(): CartId
    {
        return $this->cartId;
    }

    public function getSku(): Sku
    {
        return $this->sku;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
