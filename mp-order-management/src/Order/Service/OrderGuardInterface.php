<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;

interface OrderGuardInterface
{
    public function ensureExists(Order $order): void;
}
