<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;

interface OrderServiceInterface
{
    public function createNewOrder(array $data): Order;

    public function validateOrder(Order $order): bool;
}
