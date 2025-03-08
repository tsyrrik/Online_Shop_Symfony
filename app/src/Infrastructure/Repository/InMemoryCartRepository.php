<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use Override;
use Ramsey\Uuid\UuidInterface;

final class InMemoryCartRepository implements CartRepositoryInterface
{
    /** @var array<string, Cart> */
    private array $carts = [];

    #[Override]
    public function getCartForUser(UuidInterface $userId): ?Cart
    {
        return $this->carts[$userId->toString()] ?? null;
    }

    #[Override]
    public function saveCart(UuidInterface $userId, Cart $cart): void
    {
        $this->carts[$userId->toString()] = $cart;
    }

    #[Override]
    public function findCompletedCarts(): array
    {
        return array_filter(array: $this->carts, callback: static fn(Cart $cart) => $cart->isCompleted());
    }
}
