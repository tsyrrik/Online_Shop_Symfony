<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use Override;

final class InMemoryCartRepository implements CartRepositoryInterface
{
    /** @var array<string, Cart> */
    private array $carts = [];

    #[Override]
    public function getCartForUser(string $userId): ?Cart
    {
        return $this->carts[$userId] ?? null;
    }

    #[Override]
    public function saveCart(string $userId, Cart $cart): void
    {
        $this->carts[$userId] = $cart;
    }

    #[Override]
    public function findCompletedCarts(): array
    {
        return array_filter($this->carts, static fn(Cart $cart) => $cart->isCompleted());
    }

    #[Override]
    public function getOpenCartForUser(string $userId): ?Cart
    {
        $cart = $this->getCartForUser($userId);
        if ($cart && !$cart->isCompleted()) {
            return $cart;
        }

        return null;
    }
}
