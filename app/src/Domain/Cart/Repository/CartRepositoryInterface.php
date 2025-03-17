<?php

declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Cart;

interface CartRepositoryInterface
{
    public function getCartForUser(string $userId): ?Cart;

    public function getOpenCartForUser(string $userId): ?Cart;

    public function saveCart(string $userId, Cart $cart): void;

    public function findCompletedCarts(): array;
}
