<?php

namespace App\Cart\Domain\Exception;

final class CustomerNotFoundException extends \DomainException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withId(int $id): self
    {
        return new self("Customer with id {$id} not found");
    }
}
