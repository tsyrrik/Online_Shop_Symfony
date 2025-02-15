<?php

declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Cart;

interface CartRepositoryInterface
{
    public function getCartForUser(int $userId): ?Cart;

    public function saveCart(int $userId, Cart $cart): void;
}
