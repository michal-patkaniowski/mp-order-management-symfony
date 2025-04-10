<?php

declare(strict_types=1);

namespace App\Order\Entity;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';

    public static function getValidValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
