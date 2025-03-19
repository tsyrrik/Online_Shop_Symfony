<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Domain\ValueObject\UuidV7;
use Doctrine\ORM\EntityManagerInterface;
use Override;

final class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Override]
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    #[Override]
    public function findByEmail(string $email): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(criteria: ['email' => $email]);
    }

    #[Override]
    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    #[Override]
    public function findById(UuidV7 $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

    #[Override]
    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
