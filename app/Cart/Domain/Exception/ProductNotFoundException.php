<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

final class ProductNotFoundException extends \DomainException
{
    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function bySku(string $sku): self
    {
        return new self("Product with sku {$sku} not found.");
    }
}
