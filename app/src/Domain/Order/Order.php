<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Enum\OrderStatus;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Order
{
    private UuidInterface $id;

    private UuidInterface $userId;

    /** @var Collection<int, OrderItem> */
    private Collection $items;

    private OrderStatus $status;

    private string $deliveryMethod;

    private string $orderPhone;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function __construct(
        UuidInterface $userId,
        Collection $items,
        string $deliveryMethod,
        string $orderPhone,
    ) {
        /** @var Collection<int, OrderItem> $items */
        $this->id = Uuid::uuid7();
        $this->userId = $userId;
        $this->items = $items;
        $this->status = OrderStatus::PAID;
        $this->deliveryMethod = $deliveryMethod;
        $this->orderPhone = $orderPhone;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getDeliveryMethod(): string
    {
        return $this->deliveryMethod;
    }

    public function getOrderPhone(): string
    {
        return $this->orderPhone;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status;
        $this->updatedAt = new DateTimeImmutable();
    }
}
