<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;

interface OrderServiceInterface
{
    public function createNewOrder(array $data): Order;
}
