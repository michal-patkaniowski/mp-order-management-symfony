<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;
use App\Order\Entity\OrderStatus;
use App\Order\Validator\StatusTransition;
use InvalidArgumentException;
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

    public function ensureNewStatusIsValid(Order $order, ?string $newStatus): void
    {
        if ($newStatus === null) {
            throw new InvalidArgumentException('New status cannot be null.');
        }

        $status = OrderStatus::tryFrom($newStatus);

        if ($status === null) {
            throw new InvalidArgumentException(sprintf('Invalid status: %s', $newStatus));
        }

        $clonedOrder = clone $order;
        $clonedOrder->setStatus($status);

        $violations = $this->validator->validate($clonedOrder, null, [StatusTransition::class]);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }
}
