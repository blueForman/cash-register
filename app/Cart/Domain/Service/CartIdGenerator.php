<?php

namespace App\Cart\Domain\Service;

use Ramsey\Uuid\Uuid;

final class CartIdGenerator
{
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
