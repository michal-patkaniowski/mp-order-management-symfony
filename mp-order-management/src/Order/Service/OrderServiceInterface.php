<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;

interface OrderServiceInterface
{
    public function findOrder(string $id): ?Order;

    public function createNewOrder(array $data): Order;

    public function changeOrderStatus(Order $order, string $newStatus): void;
}
