<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;
use App\Order\Factory\OrderFactoryInterface;
use App\Order\Repository\OrderRepositoryInterface;
use App\Order\Service\OrderSerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderFactoryInterface $orderFactory,
        private OrderSerializerInterface $orderSerializer,
        private ValidatorInterface $validator
    ) {
    }

    public function createNewOrder(array $data): Order
    {
        $orderData = $this->orderSerializer->denormalize($data, [Order::GROUP_GENERAL, Order::GROUP_ITEMS]);
        $order = $this->orderFactory->create($orderData);
        $this->validateOrder($order);

        $this->orderRepository->create($order);

        return $order;
    }

    public function validateOrder(Order $order): bool
    {
        $violations = $this->validator->validate($order, null, [Order::GROUP_GENERAL, Order::GROUP_ITEMS]);

        //TODO simplify - return array of string messages, use array fn
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            throw new InvalidArgumentException(implode(', ', $errors));
        }

        return true;
    }
}
