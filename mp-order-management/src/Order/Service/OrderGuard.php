<?php

declare(strict_types=1);

namespace App\Order\Service;

use InvalidArgumentException;
use App\Order\Entity\Order;
use Symfony\Component\Uid\Uuid;

class OrderGuard implements OrderGuardInterface
{
    public function ensureExists(?Order $order): void
    {
        if ($order === null) {
            throw new InvalidArgumentException('Order does not exist.');
        }
    }

    public function ensureUuidIsValid(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('Invalid UUID provided.');
        }
    }
}
