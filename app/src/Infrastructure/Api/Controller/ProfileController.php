<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class ProfileController
{
    public function __construct(
        private Security $security,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    #[Route('/profile', name: 'profile_view', methods: ['GET'])]
    public function view(): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $orders = $this->orderRepository->findByUserId($user->getId());
        $ordersData = array_map(static fn($order) => [
            'id' => $order->getId()->toString(),
            'status' => $order->getStatus()->value,
            'deliveryMethod' => $order->getDeliveryMethod(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $orders);

        return new JsonResponse([
            'user' => [
                'id' => $user->getId()->toString(),
                'name' => $user->getName(),
                'phone' => $user->getPhone(),
                'email' => $user->getEmail(),
            ],
            'orders' => $ordersData,
        ]);
    }
}
