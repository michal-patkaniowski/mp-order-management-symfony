<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Order\Entity\Order;

interface OrderGuardInterface
{
    public function ensureExists(?Order $order): void;

    public function ensureUuidIsValid(string $uuid): void;

    public function getValidationErrors(Order $order): array;

    public function ensureIsValid(Order $order): void;

    public function ensureNewStatusIsValid(Order $order, string $newStatus): void;
}
