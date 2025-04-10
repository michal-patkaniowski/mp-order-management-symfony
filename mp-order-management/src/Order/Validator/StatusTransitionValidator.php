<?php

declare(strict_types=1);

namespace App\Order\Validator;

use App\Order\Entity\Order;
use App\Order\Entity\OrderStatus;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StatusTransitionValidator extends ConstraintValidator
{
    private const PERMITTED_STATUS_TRANSITIONS = [
        OrderStatus::NEW->value => [OrderStatus::PAID->value, OrderStatus::CANCELLED->value],
        OrderStatus::PAID->value => [OrderStatus::NEW->value, OrderStatus::SHIPPED->value],
        OrderStatus::SHIPPED->value => [], // cannot transition to any status
        OrderStatus::CANCELLED->value => [OrderStatus::NEW->value],
    ];

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof StatusTransition) {
            throw new UnexpectedTypeException($constraint, StatusTransition::class);
        }

        if (!$value instanceof Order) {
            throw new UnexpectedTypeException($value, Order::class);
        }

        $currentStatus = $value->getStatus();
        $previousStatus = $value->getPreviousStatus();

        if ($previousStatus === null) {
            return;
        }

        if ($previousStatus === $currentStatus) {
            $this->context->buildViolation('Order status has not changed.')
                ->addViolation();
            return;
        }

        if (!array_key_exists($previousStatus->value, self::PERMITTED_STATUS_TRANSITIONS)) {
            $this->context->buildViolation('Order status is not valid for this operation.')
                ->addViolation();
            return;
        }

        $allowedStatuses = self::PERMITTED_STATUS_TRANSITIONS[$previousStatus->value] ?? null;

        if (
            $allowedStatuses !== null && !in_array($currentStatus->value, $allowedStatuses, true)
        ) {
            $this->context->buildViolation(sprintf(
                'Order with status "%s" can only transition to: %s.',
                $previousStatus->value,
                implode(', ', $allowedStatuses)
            ))->addViolation();
        }
    }
}
