<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Enum\OrderStatus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class AdminOrderController
{
    public function __construct(private OrderRepositoryInterface $orderRepository) {}

    #[Route('/admin/orders', name: 'admin_orders_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        $data = array_map(static fn($order) => [
            'id' => $order->getId()->toString(),
            'userId' => $order->getUserId()->toString(),
            'status' => $order->getStatus()->value,
            'deliveryMethod' => $order->getDeliveryMethod(),
            'orderPhone' => $order->getOrderPhone(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $orders);

        return new JsonResponse($data);
    }

    #[Route('/admin/orders/{orderId}/status', name: 'admin_order_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, string $orderId): JsonResponse
    {
        $order = $this->orderRepository->findById(Uuid::fromString($orderId));
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Status is required'], 400);
        }

        $newStatus = OrderStatus::from($data['status']);
        $order->setStatus($newStatus);
        $this->orderRepository->save($order);

        return new JsonResponse(['status' => 'Order status updated']);
    }
}
