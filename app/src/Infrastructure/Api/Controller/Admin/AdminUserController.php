<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminUserController
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    #[Route('/admin/users', name: 'admin_users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = array_map(callback: static fn($user) => [
            'id' => $user->getId()->toString(),
            'name' => $user->getName(),
            'phone' => $user->getPhone(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ], array: $users);

        return new JsonResponse(data: $data);
    }
}
