<?php

declare(strict_types=1);

namespace App\Order\Service;

use InvalidArgumentException;
use App\Order\Entity\Order;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderGuard implements OrderGuardInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

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

    public function getValidationErrors(Order $order): array
    {
        $violations = $this->validator->validate($order, null, [Order::GROUP_GENERAL, Order::GROUP_ITEMS]);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        return $errors;
    }

    public function ensureIsValid(Order $order): void
    {
        $errors = $this->getValidationErrors($order);

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }
}
