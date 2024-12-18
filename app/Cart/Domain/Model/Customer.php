<?php
declare(strict_types=1);
namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\CustomerId;

final class Customer
{
    public function __construct(private readonly CustomerId $id)
    {
    }

    public function getId(): CustomerId
    {
        return $this->id;
    }
}
