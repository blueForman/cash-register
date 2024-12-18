<?php
declare(strict_types=1);
namespace App\Cart\Domain\Service;

use App\Cart\Domain\Value\CartId;
use Ramsey\Uuid\Uuid;

final class CartIdGenerator
{
    public static function generate(): CartId
    {
        return new CartId(Uuid::uuid4()->toString());
    }
}
