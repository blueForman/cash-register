<?php
declare(strict_types=1);
namespace App\Cart\Domain\Service;

use App\Cart\Domain\Value\CartId;
use Ramsey\Uuid\Uuid;

final class IdGenerator
{
    public static function generateCartId(): CartId
    {
        return new CartId(Uuid::uuid4()->toString());
    }

    public static function generateUniqueId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
