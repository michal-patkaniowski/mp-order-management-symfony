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

        $statusTransitions = [
            OrderStatus::NEW => null, // can transition to any status
            OrderStatus::PAID => [OrderStatus::NEW , OrderStatus::SHIPPED],
            OrderStatus::SHIPPED => [], // cannot transition to any status
            OrderStatus::CANCELLED => [OrderStatus::NEW],
        ];

        if (!array_key_exists($currentStatus->value, $statusTransitions)) {
            $this->context->buildViolation('Order status is not valid for this operation.')
                ->addViolation();
            return;
        }

        $allowedStatuses = $statusTransitions[$currentStatus->value] ?? null;

        if (
            $allowedStatuses !== null && $previousStatus !== null &&
            !in_array($previousStatus->value, $allowedStatuses, true)
        ) {
            $this->context->buildViolation(sprintf(
                'Order with status "%s" can only transition from: %s.',
                $currentStatus,
                implode(', ', $allowedStatuses)
            ))->addViolation();
        }
    }
}
