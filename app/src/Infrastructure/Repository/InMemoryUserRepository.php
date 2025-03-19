<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Domain\ValueObject\UuidV7;

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

    public function findById(UuidV7 $id): ?User
    {
        foreach ($this->users as $user) {
            if ((string) $user->getId() === (string) $id) {
                return $user;
            }
        }

        return null;
    }

    public function delete(User $user): void
    {
        foreach ($this->users as $key => $existingUser) {
            if ((string) $existingUser->getId() === (string) $user->getId()) {
                unset($this->users[$key]);
                $this->users = array_values(array: $this->users);

                return;
            }
        }
    }
}
