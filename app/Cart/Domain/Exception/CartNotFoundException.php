<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

final class CartNotFoundException extends \DomainException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function byCartId(string $id): self
    {
        return new self("Cart with id {$id} not found");
    }
}
