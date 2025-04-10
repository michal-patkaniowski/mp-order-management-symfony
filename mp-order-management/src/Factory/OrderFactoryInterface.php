<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order;

interface OrderFactoryInterface
{
    public function create(array $data): Order;
}
