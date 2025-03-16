<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Infrastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InMemoryCartRepositoryTest extends TestCase
{
    public function testSaveAndGetCartSucceeds(): void
    {
        // Arrange
        $repository = new InMemoryCartRepository();
        $userId = Uuid::uuid7();
        $cart = new Cart(userId: $userId);
        $nonExistentUserId = Uuid::uuid7();

        // Act
        $repository->saveCart(userId: $userId, cart: $cart);
        $retrievedCart = $repository->getCartForUser(userId: $userId);
        $cartForNonExistentUser = $repository->getCartForUser(userId: $nonExistentUserId);

        // Assert
        self::assertSame(expected: $cart, actual: $retrievedCart);
        self::assertNull(actual: $cartForNonExistentUser);
    }
}
