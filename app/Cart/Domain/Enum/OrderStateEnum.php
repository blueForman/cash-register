<?php

declare(strict_types=1);

namespace App\Cart\Domain\Enum;

enum OrderStateEnum
{
    case NEW;
    case PROCESSED;
    case SHIPPED;
    CASE DELIVERED;
    case CANCELLED;

}

