<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Infrastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCartRepository extends TestCase
{
    public function testSaveAndGetCartSucceeds(): void
    {
        // Arrange
        $repository = new self();
        $cart = new Cart(userId: 1);
        $nonExistentUserId = 2;

        // Act
        $repository->saveCart(userId: 1, cart: $cart);
        $retrievedCart = $repository->getCartForUser(userId: 1);
        $cartForNonExistentUser = $repository->getCartForUser(userId: $nonExistentUserId);

        // Assert
        self::assertSame(expected: $cart, actual: $retrievedCart);
        self::assertNull(actual: $cartForNonExistentUser);
    }
}
