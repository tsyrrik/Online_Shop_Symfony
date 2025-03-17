<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Domain\ValueObject\UuidV7;
use App\Infrastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCartRepositoryTest extends TestCase
{
    public function testSaveAndGetCartSucceeds(): void
    {
        // Arrange
        $repository = new InMemoryCartRepository();
        $userId = new UuidV7();
        $cart = new Cart($userId);
        $nonExistentUserId = (new UuidV7())->toString();

        // Act
        $repository->saveCart($userId->toString(), $cart);
        $retrievedCart = $repository->getCartForUser($userId->toString());
        $cartForNonExistentUser = $repository->getCartForUser($nonExistentUserId);

        // Assert
        self::assertSame($cart, $retrievedCart);
        self::assertNull($cartForNonExistentUser);
    }
}
