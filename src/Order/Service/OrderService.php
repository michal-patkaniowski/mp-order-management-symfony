<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;
use App\Order\Entity\OrderStatus;
use App\Order\Factory\OrderFactoryInterface;
use App\Order\Repository\OrderRepositoryInterface;
use App\Order\Service\OrderSerializerInterface;
use App\Order\Service\OrderGuardInterface;
use InvalidArgumentException;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderFactoryInterface $orderFactory,
        private OrderSerializerInterface $orderSerializer,
        private OrderGuardInterface $orderGuard
    ) {
    }

    public function findOrder(string $uuid): Order
    {
        $order = $this->orderRepository->find($uuid);
        $this->orderGuard->ensureExists($order);

        return $order;
    }

    public function createNewOrder(array $data): Order
    {
        $orderData = $this->orderSerializer->denormalize($data, [Order::GROUP_GENERAL, Order::GROUP_ITEMS]);
        $order = $this->orderFactory->create($orderData);
        $this->orderGuard->ensureIsValid($order);

        $this->orderRepository->create($order);

        return $order;
    }

    public function changeOrderStatus(Order $order, ?string $newStatus): void
    {
        $this->orderGuard->ensureNewStatusIsValid($order, $newStatus);

        $status = OrderStatus::from($newStatus);
        $order->setStatus($status);

        $this->orderRepository->create($order);
    }
}
