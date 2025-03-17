<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Order;
use App\Domain\ValueObject\UuidV7;

interface OrderRepositoryInterface
{
    public function findById(UuidV7 $id): ?Order;

    public function findAll(): array;

    public function findByUserId(UuidV7 $userId): array;

    public function save(Order $order): void;
}
