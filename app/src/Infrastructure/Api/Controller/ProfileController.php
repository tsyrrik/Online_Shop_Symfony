<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Api\DTO\ProfileResponseDTO;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ProfileController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private OrderRepositoryInterface $orderRepository,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/profile', name: 'profile_view', methods: ['GET'])]
    #[OA\Get(
        path: '/profile',
        summary: 'Get user profile data',
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Profile data',
                content: new OA\JsonContent(ref: '#/components/schemas/ProfileResponse'),
            ),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Unauthorized', headers: []),
        ],
    )]
    public function view(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        if (!$token || !$token->getUser() instanceof User) {
            return new JsonResponse(data: ['error' => 'Unauthorized'], status: Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = $token->getUser();
        $orders = $this->orderRepository->findByUserId($user->getId());
        $ordersData = array_map(callback: static fn($order) => [
            'id' => $order->getId()->toString(),
            'status' => $order->getStatus()->value,
            'deliveryMethod' => $order->getDeliveryMethod()->value,
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
        ], array: $orders);

        $responseDTO = new ProfileResponseDTO(
            user: [
                'id' => $user->getId()->toString(),
                'name' => $user->getName(),
                'phone' => $user->getPhone(),
                'email' => $user->getEmail(),
            ],
            orders: $ordersData,
        );

        $json = $this->serializer->serialize($responseDTO, 'json');

        return new JsonResponse(data: $json, json: true);
    }
}
