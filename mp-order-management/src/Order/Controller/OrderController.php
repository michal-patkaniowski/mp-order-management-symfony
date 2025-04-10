<?php

declare(strict_types=1);

namespace App\Order\Controller;

use App\Order\Entity\Order;
use App\Order\Repository\OrderRepositoryInterface;
use App\Order\Service\OrderServiceInterface;
use App\Order\Service\OrderGuardInterface;
use App\Order\Service\OrderSerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders', name: 'order_')]
class OrderController extends AbstractController
{
    private OrderSerializerInterface $orderSerializer;
    private OrderRepositoryInterface $orderRepository;
    private OrderServiceInterface $orderService;
    private OrderGuardInterface $orderGuard;

    public function __construct(
        OrderSerializerInterface $orderSerializer,
        OrderRepositoryInterface $orderRepository,
        OrderServiceInterface $orderService,
        OrderGuardInterface $orderGuard
    ) {
        $this->orderSerializer = $orderSerializer;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->orderGuard = $orderGuard;
    }

    #[Route('/{uuid}', name: 'get', methods: ['GET'])]
    public function getOrderAction(string $uuid): Response
    {
        $this->orderGuard->ensureUuidIsValid($uuid);

        $order = $this->orderRepository->find($uuid);
        $this->orderGuard->ensureExists($order);

        return new Response($this->orderSerializer->serialize(
            $order,
            [Order::GROUP_GENERAL, Order::GROUP_ITEMS]
        ));
    }

    #[Route('', name: 'post', methods: ['POST'])]
    public function postOrderAction(Request $request): Response
    {
        $order = $this->orderService->createNewOrder($request->toArray());

        return new Response($this->orderSerializer->serialize(
            $order,
            [Order::GROUP_GENERAL, Order::GROUP_ITEMS]
        ), Response::HTTP_CREATED);
    }

    #[Route('/{uuid}', name: 'patch_status', methods: ['PATCH'])]
    public function patchOrderStatusAction(string $uuid, Request $request): Response
    {
        $this->orderGuard->ensureUuidIsValid($uuid);

        $order = $this->orderRepository->find($uuid);
        $this->orderGuard->ensureExists($order);

        $newStatus = $request->get('status');
        $this->orderService->changeOrderStatus($order, $newStatus);

        return new Response($this->orderSerializer->serialize(
            $order,
            [Order::GROUP_STATUS]
        ));
    }
}
