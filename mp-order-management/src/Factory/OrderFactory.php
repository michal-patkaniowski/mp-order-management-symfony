<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order;
use Symfony\Component\Uid\Uuid;

class OrderFactory implements OrderFactoryInterface
{
    public function create(array $data): Order
    {
        $order = new Order();

        $order->setId(Uuid::v4()->toRfc4122());
        $order->setCreatedAt(new \DateTime());
        $order->setStatus('new');

        return $order;
    }
}
