<?php
declare(strict_types=1);
namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\CustomerId;

final class Customer
{
    public function __construct(
        private readonly CustomerId $id,
        private readonly string $name,
        private readonly string $email,
    )
    {
    }

    public function getId(): CustomerId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
