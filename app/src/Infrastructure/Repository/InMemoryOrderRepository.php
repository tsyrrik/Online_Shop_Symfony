<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Order\Order;
use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\ValueObject\UuidV7;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /** @var array<string, Order> */
    private array $orders = [];

    public function findById(UuidV7 $id): ?Order
    {
        return $this->orders[$id->toString()] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->orders);
    }

    public function findByUserId(UuidV7 $userId): array
    {
        return array_filter(
            $this->orders,
            static fn(Order $order) => $order->getUserId()->toString() === $userId->toString(), // Changed to toString() comparison
        );
    }

    public function save(Order $order): void
    {
        $this->orders[$order->getId()->toString()] = $order;
    }
}
