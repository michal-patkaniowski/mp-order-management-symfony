<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrderRepository implements OrderRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $id): ?Order
    {
        return $this->entityManager->getRepository(Order::class)->find($id);
    }

    public function create(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
