<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var array<User> */
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->users;
    }
}
