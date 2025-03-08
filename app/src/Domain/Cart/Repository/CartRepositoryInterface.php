<?php

declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Cart;
use Ramsey\Uuid\UuidInterface;

interface CartRepositoryInterface
{
    public function getCartForUser(UuidInterface $userId): ?Cart;

    public function saveCart(UuidInterface $userId, Cart $cart): void;

    public function findCompletedCarts(): array;
}
