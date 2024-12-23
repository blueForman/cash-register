<?php
declare(strict_types=1);
namespace App\Cart\Domain\Exception;

final class CustomerNotFoundException extends \DomainException
{
    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withId(int $id): self
    {
        return new self("Customer with id {$id} not found");
    }
}
