<?php

declare(strict_types=1);

namespace App\Domain\Order;

use Ramsey\Uuid\UuidInterface;

final class OrderItem
{
    private UuidInterface $productId;

    private string $productName;

    private int $quantity;

    private int $priceAtPurchase;

    public function __construct(UuidInterface $productId, string $productName, int $quantity, int $priceAtPurchase)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->priceAtPurchase = $priceAtPurchase;
    }

    public function getProductId(): UuidInterface
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
