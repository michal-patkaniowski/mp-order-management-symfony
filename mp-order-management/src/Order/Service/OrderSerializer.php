<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;
use Symfony\Component\Serializer\SerializerInterface;

class OrderSerializer implements OrderSerializerInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(Order $order, array $groups = []): string
    {
        return $this->serializer->serialize($order, 'json', ['groups' => $groups]);
    }

    public function deserialize(array $data, array $groups = []): Order
    {
        return $this->serializer->deserialize($data, Order::class, 'json', ['groups' => $groups]);
    }
}
