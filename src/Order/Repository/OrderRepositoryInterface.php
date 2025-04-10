<?php

declare(strict_types=1);

namespace App\Order\Repository;

use App\Order\Entity\Order;

interface OrderRepositoryInterface
{
    public function find(string $id): ?Order;

    public function create(Order $order): void;
}
