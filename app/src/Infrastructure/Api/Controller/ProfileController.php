<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ProfileController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    #[Route('/profile', name: 'profile_view', methods: ['GET'])]
    public function view(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return new JsonResponse(data: ['error' => 'Unauthorized'], status: Response::HTTP_UNAUTHORIZED);
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(data: ['error' => 'Unauthorized'], status: Response::HTTP_UNAUTHORIZED);
        }
        $orders = $this->orderRepository->findByUserId($user->getId());
        $ordersData = array_map(callback: static fn($order) => [
            'id' => $order->getId()->toString(),
            'status' => $order->getStatus()->value,
            'deliveryMethod' => $order->getDeliveryMethod(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
        ], array: $orders);

        return new JsonResponse(data: [
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
