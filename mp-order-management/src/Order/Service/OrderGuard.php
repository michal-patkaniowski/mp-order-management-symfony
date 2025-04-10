<?php

declare(strict_types=1);

namespace App\Order\Service;

use InvalidArgumentException;
use App\Order\Entity\Order;

class OrderGuard implements OrderGuardInterface
{
    public function ensureExists(Order $order): void
    {
        if (!($order instanceof Order)) {
            throw new InvalidArgumentException('Invalid order object provided.');
        }
        if ($order === null) {
            throw new InvalidArgumentException('Order does not exist.');
        }
    }
}
