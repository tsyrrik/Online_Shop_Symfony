<?php

declare(strict_types=1);

namespace App\Application\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class AddToCartCommand
{
    public function __construct(private UuidInterface $userId, private UuidInterface $productId, private int $quantity = 1) {}

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
