<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use Override;

final class InMemoryCartRepository implements CartRepositoryInterface
{
    /** @var array<int, Cart> */
    private array $carts = [];

    #[Override]
    public function getCartForUser(int $userId): ?Cart
    {
        return $this->carts[$userId] ?? null;
    }

    #[Override]
    public function saveCart(int $userId, Cart $cart): void
    {
        $this->carts[$userId] = $cart;
    }
}
