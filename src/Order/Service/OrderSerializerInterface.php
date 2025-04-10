<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;

interface OrderSerializerInterface
{
    public function serialize(Order $order, array $groups): string;

    public function denormalize(array $data, array $groups): object;
}
