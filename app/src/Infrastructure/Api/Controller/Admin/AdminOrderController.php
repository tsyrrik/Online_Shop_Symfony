<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\ValueObject\UuidV7;
use App\Enum\OrderStatus;
use App\Infrastructure\Api\DTO\OrderDTO;
use App\Infrastructure\Api\DTO\OrderItemDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use ValueError;

#[Route('/admin/orders')]
final class AdminOrderController extends AbstractController
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private SerializerInterface $serializer,
    ) {}

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        $orderDTOs = array_map(
            callback: static fn($order) => new OrderDTO(
                id: $order->getId()->toString(),
                userId: $order->getUserId()->toString(),
                status: $order->getStatus()->value,
                deliveryMethod: $order->getDeliveryMethod()->value,
                orderPhone: $order->getOrderPhone(),
                createdAt: $order->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
            ),
            array: $orders,
        );
        $json = $this->serializer->serialize($orderDTOs, 'json', ['groups' => ['order:read']]);

        return new JsonResponse(data: $json, json: true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $order = $this->orderRepository->findById(new UuidV7(uuid: $id));
        if (!$order) {
            return new JsonResponse(data: ['error' => 'Order not found'], status: Response::HTTP_NOT_FOUND);
        }

        $orderItems = array_map(
            callback: static fn($item) => new OrderItemDTO(
                productId: $item->getProductId()->toString(),
                productName: $item->getProductName(),
                quantity: $item->getQuantity(),
                priceAtPurchase: $item->getPriceAtPurchase(),
            ),
            array: $order->getItems()->toArray(),
        );

        $orderDTO = new OrderDTO(
            id: $order->getId()->toString(),
            userId: $order->getUserId()->toString(),
            status: $order->getStatus()->value,
            deliveryMethod: $order->getDeliveryMethod()->value,
            orderPhone: $order->getOrderPhone(),
            createdAt: $order->getCreatedAt()->format(format: 'Y-m-d\TH:i:s\Z'),
            items: $orderItems,
        );

        $json = $this->serializer->serialize($orderDTO, 'json', ['groups' => ['order:read']]);

        return new JsonResponse(data: $json, json: true);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $order = $this->orderRepository->findById(new UuidV7(uuid: $id));
        if (!$order) {
            return new JsonResponse(data: ['error' => 'Order not found'], status: Response::HTTP_NOT_FOUND);
        }

        $data = json_decode(json: $request->getContent(), associative: true);
        if (!isset($data['status'])) {
            return new JsonResponse(data: ['error' => 'Status is required'], status: Response::HTTP_BAD_REQUEST);
        }

        try {
            $newStatus = OrderStatus::from(value: $data['status']);
            $order->setStatus(status: $newStatus);
            $this->orderRepository->save($order);

            return new JsonResponse(data: ['status' => 'Order updated'], status: Response::HTTP_OK);
        } catch (ValueError $e) {
            return new JsonResponse(data: ['error' => 'Invalid status value'], status: Response::HTTP_BAD_REQUEST);
        }
    }
}
