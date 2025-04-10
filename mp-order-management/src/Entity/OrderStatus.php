<?php

declare(strict_types=1);

namespace App\Entity;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
}
