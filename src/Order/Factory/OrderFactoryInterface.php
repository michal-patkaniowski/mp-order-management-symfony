<?php

declare(strict_types=1);

namespace App\Order\Factory;

use App\Order\Entity\Order;

interface OrderFactoryInterface
{
    public function create(Order $data): Order;
}
