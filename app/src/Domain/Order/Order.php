<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\ValueObject\UuidV7;
use App\Enum\DeliveryMethod;
use App\Enum\OrderStatus;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

final class Order
{
    private UuidV7 $id;

    private OrderStatus $status;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    /**
     * @param Collection<int, OrderItem> $items
     */
    public function __construct(
        private UuidV7 $userId,
        private Collection $items,
        private DeliveryMethod $deliveryMethod,
        private string $orderPhone,
    ) {
        $this->id = new UuidV7();
        $this->status = OrderStatus::PAID;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getUserId(): UuidV7
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

    public function getDeliveryMethod(): DeliveryMethod
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
