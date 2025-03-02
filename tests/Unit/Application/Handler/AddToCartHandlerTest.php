<?php

namespace Tests\Unit\Application\Handler;

use App\Application\Command\AddToCartCommand;
use App\Application\Handler\AddToCartHandler;
use App\Domain\Cart\Cart;
use App\Domain\Product\Product;
use App\Infrastructure\Repository\InMemoryCartRepository;
use App\Infrastructure\Repository\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class AddToCartHandlerTest extends TestCase
{
    private AddToCartHandler $handler;
    private InMemoryProductRepository $productRepository;
    private InMemoryCartRepository $cartRepository;

    protected function setUp(): void
    {
        $this->productRepository = new InMemoryProductRepository();
        $this->cartRepository = new InMemoryCartRepository();
        $this->handler = new AddToCartHandler($this->productRepository, $this->cartRepository);
    }

    public function testHandleWithExistingCart(): void
    {
        // Arrange
        $product = new Product('Test Product', 100, 20, 30, 40, 500, 50, 1, 'Description');
        $reflection = new \ReflectionClass($product);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($product, 2);

        $this->productRepository->save($product);

        $cart = new Cart(1);
        $this->cartRepository->saveCart(1, $cart);

        $command = new AddToCartCommand(1, 2, 3);

        // Act
        $this->handler->handle($command);

        // Assert
        $updatedCart = $this->cartRepository->getCartForUser(1);
        $this->assertCount(1, $updatedCart->getItems());
        $this->assertEquals(3, $updatedCart->getTotalQuantity());
    }

    public function testHandleProductNotFound(): void
    {
        // Arrange
        $command = new AddToCartCommand(1, 999, 3); //

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Товар не найден');

        // Act
        $this->handler->handle($command);
    }
}