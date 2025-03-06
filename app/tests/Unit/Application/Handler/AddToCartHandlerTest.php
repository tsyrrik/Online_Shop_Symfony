<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handler;

use App\Application\Command\AddToCartCommand;
use App\Application\Handler\AddToCartHandler;
use App\Domain\Cart\Cart;
use App\Domain\Product\Product;
use App\Infrastructure\Repository\InMemoryCartRepository;
use App\Infrastructure\Repository\InMemoryProductRepository;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AddToCartHandlerTest extends TestCase
{
    private AddToCartHandler $handler;

    private InMemoryProductRepository $productRepository;

    private InMemoryCartRepository $cartRepository;

    protected function setUp(): void
    {
        $this->productRepository = new InMemoryProductRepository();
        $this->cartRepository = new InMemoryCartRepository();
        $this->handler = new AddToCartHandler(productRepository: $this->productRepository, cartRepository: $this->cartRepository);
    }

    public function testHandleWithExistingCart(): void
    {
        // Arrange
        $product = new Product(name: 'Test Product', weight: 100, height: 20, width: 30, length: 40, cost: 500, tax: 50, version: 1, description: 'Description');
        $reflection = new ReflectionClass(objectOrClass: $product);
        $property = $reflection->getProperty(name: 'id');
        $property->setAccessible(accessible: true);
        $property->setValue(objectOrValue: $product, value: 2);

        $this->productRepository->save(product: $product);

        $cart = new Cart(userId: 1);
        $this->cartRepository->saveCart(userId: 1, cart: $cart);

        $command = new AddToCartCommand(userId: 1, productId: 2, quantity: 3);

        // Act
        $this->handler->handle(command: $command);

        // Assert
        $updatedCart = $this->cartRepository->getCartForUser(userId: 1);
        self::assertCount(expectedCount: 1, haystack: $updatedCart->getItems());
        self::assertEquals(expected: 3, actual: $updatedCart->getTotalQuantity());
    }

    public function testHandleProductNotFound(): void
    {
        // Arrange
        $command = new AddToCartCommand(userId: 1, productId: 999, quantity: 3);

        // Assert
        $this->expectException(exception: Exception::class);
        $this->expectExceptionMessage(message: 'Товар не найден');

        // Act
        $this->handler->handle(command: $command);
    }
}
