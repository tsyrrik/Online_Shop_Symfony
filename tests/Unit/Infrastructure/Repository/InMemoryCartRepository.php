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
        $cart = new Cart(1);
        $repository->saveCart(1, $cart);

        $retrievedCart = $repository->getCartForUser(1);
        $this->assertSame($cart, $retrievedCart);
        $this->assertNull($repository->getCartForUser(2));
    }
}