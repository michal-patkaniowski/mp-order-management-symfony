<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepositoryInterface;
use App\Service\OrderService;
use App\Service\OrderServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Uid\Uuid;

class OrderController extends AbstractController
{
    private SerializerInterface $serializer;
    private OrderRepositoryInterface $orderRepository;
    private OrderServiceInterface $orderService;

    public function __construct(
        SerializerInterface $serializer,
        OrderRepositoryInterface $orderRepository,
        OrderServiceInterface $orderService
    ) {
        $this->serializer = $serializer;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    public function getOrderAction(string $uuid): Response
    {
        $order = $this->orderRepository->find($uuid);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        return new Response($this->serializer->serialize(
            $order,
            'json',
            ['groups' => [Order::GROUP_GENERAL, Order::GROUP_DETAILS]]
        ));
    }

    public function postOrderAction(Request $request): Response
    {
        $order = $this->orderService->createNewOrder($request->toArray());

        return new Response($this->serializer->serialize(
            $order,
            'json',
            ['groups' => [Order::GROUP_GENERAL, Order::GROUP_DETAILS]]
        ), Response::HTTP_CREATED);
    }
}
