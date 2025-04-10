<?php

declare(strict_types=1);

namespace App\Order\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class StatusTransition extends Constraint
{
    public string $message = 'Invalid status transition.';
}
