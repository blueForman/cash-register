<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

final class InvalidQuantityException extends \DomainException
{
    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function becauseAddQuantityIsZeroOrLower(): self
    {
        return new self("Quantity can't be zero or lower than the zero");
    }
}
