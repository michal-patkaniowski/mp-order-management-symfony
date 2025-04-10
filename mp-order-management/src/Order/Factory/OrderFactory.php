<?php

declare(strict_types=1);

namespace App\Order\Factory;

use App\Order\Entity\Order;
use App\Order\Entity\OrderStatus;
use Symfony\Component\Uid\Uuid;
use DateTime;

class OrderFactory implements OrderFactoryInterface
{
    public function create(Order $order): Order
    {
        $order->setId(Uuid::v4()->toRfc4122());
        $order->setCreatedAt(new DateTime());
        $order->setStatus(OrderStatus::NEW);
        $order->updateTotal();

        foreach ($order->getItems() as $item) {
            $item->setId(Uuid::v4()->toRfc4122());
            $item->setOrder($order);
        }

        return $order;
    }
}
