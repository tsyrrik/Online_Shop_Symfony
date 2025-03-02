<?php

namespace Tests\Unit\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Infrastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCartRepositoryTest extends TestCase
{
    public function testSaveAndGetCart()
    {
        $repository = new InMemoryCartRepository();
        $cart = new Cart(userId: 1);
        $repository->saveCart(userId: 1, cart: $cart);

        $retrievedCart = $repository->getCartForUser(userId: 1);
        $this->assertSame(expected: $cart, actual: $retrievedCart);
        $this->assertNull(actual: $repository->getCartForUser(userId: 2));
    }
}