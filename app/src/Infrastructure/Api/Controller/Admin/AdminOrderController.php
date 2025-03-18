<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\ValueObject\UuidV7;
use App\Enum\OrderStatus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminOrderController
{
    public function __construct(private OrderRepositoryInterface $orderRepository) {}

    #[Route('/admin/orders', name: 'admin_orders_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        $data = array_map(callback: static fn($order) => [
            'id' => $order->getId()->toString(),
            'userId' => $order->getUserId()->toString(),
            'status' => $order->getStatus()->value,
            'deliveryMethod' => $order->getDeliveryMethod(),
            'orderPhone' => $order->getOrderPhone(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
        ], array: $orders);

        return new JsonResponse(data: $data);
    }

    #[Route('/admin/orders/{orderId}/status', name: 'admin_order_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, string $orderId): JsonResponse
    {
        $order = $this->orderRepository->findById(new UuidV7(uuid: $orderId));
        if (!$order) {
            return new JsonResponse(data: ['error' => 'Order not found'], status: Response::HTTP_NOT_FOUND);
        }

        $data = json_decode(json: $request->getContent(), associative: true);
        if (!isset($data['status'])) {
            return new JsonResponse(data: ['error' => 'Status is required'], status: Response::HTTP_BAD_REQUEST);
        }

        $newStatus = OrderStatus::from(value: $data['status']);
        $order->setStatus(status: $newStatus);
        $this->orderRepository->save($order);

        return new JsonResponse(data: ['status' => 'Order status updated']);
    }
}
