<?php

namespace App\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
class InMemoryCartRepository implements CartRepositoryInterface
{
    /**
     * @var Cart[]
     */
    private array $carts = [];

    public function getCartForUser(int $userId): ?Cart
    {
        return $this->carts[$userId] ?? null;
    }

    public function saveCart(int $userId, Cart $cart): void
    {
        $this->carts[$userId] = $cart;
    }
}
