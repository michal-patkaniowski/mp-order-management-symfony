<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepositoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use App\Service\OrderServiceInterface;
use App\Factory\OrderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class OrderService implements OrderServiceInterface
{
    private OrderRepositoryInterface $orderRepository;
    private OrderFactoryInterface $orderFactory;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderFactoryInterface $orderFactory,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function createNewOrder(array $data): Order
    {
        $orderData = $this->serializer->denormalize($data, Order::class, 'json');
        $order = $this->orderFactory->create($orderData);
        $this->validateOrder($order);

        $this->orderRepository->create($order);

        return $order;
    }

    public function validateOrder(Order $order): bool
    {
        $violations = $this->validator->validate($order, null, [Order::GROUP_GENERAL, Order::GROUP_DETAILS]);

        //TODO simplify - return array of string messages, use array fn
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            throw new \InvalidArgumentException(implode(', ', $errors));
        }

        return true;
    }
}
