<?php

namespace App\Application\Command;

class AddToCartCommand
{
    private int $userId;
    private int $productId;
    private int $quantity;

    public function __construct(int $userId, int $productId, int $quantity = 1)
    {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

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
