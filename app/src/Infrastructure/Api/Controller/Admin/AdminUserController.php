<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UuidV7;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
final class AdminUserController extends AbstractController
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    #[Route('', methods: ['GET'])]
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

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $user = $this->userRepository->findById(new UuidV7(uuid: $id));
        if (!$user) {
            return new JsonResponse(data: ['error' => 'User not found'], status: Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $user->getId()->toString(),
            'name' => $user->getName(),
            'phone' => $user->getPhone(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ];

        return new JsonResponse(data: $data);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->findById(new UuidV7(uuid: $id));
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], status: Response::HTTP_NOT_FOUND);
        }

        $data = json_decode(json: $request->getContent(), associative: true);
        if (isset($data['name'])) {
            $user->setName(name: $data['name']);
        }
        if (isset($data['phone'])) {
            $user->setPhone(phone: $data['phone']);
        }
        if (isset($data['email'])) {
            $user->setEmail(email: $data['email']);
        }
        if (isset($data['role'])) {
            $user->setRole(role: $data['role']);
        }

        $this->userRepository->save($user);

        return new JsonResponse(data: ['status' => 'User updated'], status: Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $user = $this->userRepository->findById(new UuidV7(uuid: $id));
        if (!$user) {
            return new JsonResponse(data: ['error' => 'User not found'], status: Response::HTTP_NOT_FOUND);
        }

        $this->userRepository->delete($user);

        return new JsonResponse(data: ['status' => 'User deleted'], status: Response::HTTP_OK);
    }
}
