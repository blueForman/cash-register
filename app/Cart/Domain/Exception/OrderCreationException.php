<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Cart\Domain\Model\Cart;

final class OrderCreationException extends \DomainException
{
    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function emptyCart(): self
    {
        return new self("Cannot create order from empty cart.");
    }
}
