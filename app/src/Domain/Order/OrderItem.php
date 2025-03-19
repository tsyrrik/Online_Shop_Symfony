<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\ValueObject\UuidV7;

final class OrderItem
{
    public function __construct(
        private UuidV7 $productId,
        private string $productName,
        private int $quantity,
        private int $priceAtPurchase,
    ) {}

    public function getProductId(): UuidV7
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPriceAtPurchase(): int
    {
        return $this->priceAtPurchase;
    }
}
