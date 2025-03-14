<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class AdminUserController
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    #[Route('/admin/users', name: 'admin_users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = array_map(static fn($user) => [
            'id' => $user->getId()->toString(),
            'name' => $user->getName(),
            'phone' => $user->getPhone(),
            'email' => $user->getEmail(),
        ], $users);

        return new JsonResponse($data);
    }
}
