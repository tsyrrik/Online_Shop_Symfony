<?php

declare(strict_types=1);

namespace App\Application\Command;

readonly class AddToCartCommand
{
    public function __construct(private int $userId, private int $productId, private int $quantity = 1) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
