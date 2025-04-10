<?php

declare(strict_types=1);

namespace App\Tests\Order\Validator;

use App\Order\Entity\Order;
use App\Order\Entity\OrderStatus;
use App\Order\Validator\StatusTransition;
use App\Order\Validator\StatusTransitionValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class StatusTransitionValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): StatusTransitionValidator
    {
        return new StatusTransitionValidator();
    }

    public function testValidStatusTransition(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::NEW);
        $order->setStatus(OrderStatus::PAID);

        $this->validator->validate($order, new StatusTransition());

        $this->assertNoViolation();
    }

    public function testInvalidStatusTransition(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::PAID);
        $order->setStatus(OrderStatus::CANCELLED);

        $this->validator->validate($order, new StatusTransition());

        $this->buildViolation('Order with status "paid" can only transition to: new, shipped.')
            ->assertRaised();
    }

    public function testNoStatusChange(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::NEW);
        $order->setStatus(OrderStatus::NEW);

        $this->validator->validate($order, new StatusTransition());

        $this->buildViolation('Order status has not changed.')
            ->assertRaised();
    }

    public function testInvalidPreviousStatus(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::SHIPPED);
        $order->setStatus(OrderStatus::NEW);

        $this->validator->validate($order, new StatusTransition());

        $this->buildViolation('Order with status "shipped" can only transition to: .')
            ->assertRaised();
    }
}
