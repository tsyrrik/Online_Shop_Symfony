<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Order;
use Ramsey\Uuid\UuidInterface;

interface OrderRepositoryInterface
{
    public function findById(UuidInterface $id): ?Order;

    public function findAll(): array;

    public function findByUserId(UuidInterface $userId): array;

    public function save(Order $order): void;
}
