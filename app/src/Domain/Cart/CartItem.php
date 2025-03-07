<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use DomainException;

final class CartItem
{
    private int $productId;

    private int $quantity;

    public function __construct(int $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function increaseQuantity(int $amount): void
    {
        $this->quantity += $amount;
    }

    public function decreaseQuantity(int $amount = 1): void
    {
        if ($this->quantity - $amount < 0) {
            throw new DomainException(message: 'Quantity cannot be less than zero.');
        }
        $this->quantity -= $amount;
    }
}
