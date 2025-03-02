<?php

namespace Tests\Unit\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Infrastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCartRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function testSaveAndGetCartSucceeds(): void
    {
        // Arrange
        $repository = new InMemoryCartRepository();
        $cart = new Cart(userId: 1);
        $nonExistentUserId = 2;

        // Act
        $repository->saveCart(userId: 1, cart: $cart);
        $retrievedCart = $repository->getCartForUser(userId: 1);
        $cartForNonExistentUser = $repository->getCartForUser(userId: $nonExistentUserId);

        // Assert
        $this->assertSame(expected: $cart, actual: $retrievedCart);
        $this->assertNull(actual: $cartForNonExistentUser);
    }
}