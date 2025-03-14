<?php

declare(strict_types=1);

namespace App\Application\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class CheckoutCommand
{
    public function __construct(
        private UuidInterface $userId,
        private string $deliveryMethod,
        private string $orderPhone,
    ) {}

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getDeliveryMethod(): string
    {
        return $this->deliveryMethod;
    }

    public function getOrderPhone(): string
    {
        return $this->orderPhone;
    }
}
