<?php

namespace App\Controller;

use App\Dto\CreateOrderDto;
use App\Service\OrderServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}
    #[Route('/order', name: 'create_order', methods: ['POST'])]
    public function createOrder(#[MapRequestPayload(
        acceptFormat: 'json',
        validationGroups: ['strict', 'read'],
        validationFailedStatusCode: Response::HTTP_BAD_REQUEST
    )]  CreateOrderDto $createOrderDto): JsonResponse
    {
        $order = $this->orderService->postOrder($createOrderDto);
        return $this->json([
            'data' => $order
        ]);
    }

    #[Route('/order', name: 'get_orders', methods: ['GET'])]
    public function getOrders(): JsonResponse
    {
        $orders = $this->orderService->getOrders();
        return $this->json([
            'data' => $orders
        ]);
    }

}
