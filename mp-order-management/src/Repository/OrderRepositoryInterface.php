<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;

interface OrderRepositoryInterface
{
    public function find(string $id): ?Order;

    public function create(Order $order): void;
}
