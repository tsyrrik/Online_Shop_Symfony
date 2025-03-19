<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\User;
use App\Domain\ValueObject\UuidV7;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    /**
     * @return User[]
     */
    public function findAll(): array;

    public function findById(UuidV7 $id): ?User;

    public function delete(User $user): void;
}
