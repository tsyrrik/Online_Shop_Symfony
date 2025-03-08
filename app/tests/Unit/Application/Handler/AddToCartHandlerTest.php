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
use Ramsey\Uuid\Uuid;

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
        $userId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $product = new Product(name: 'Test Product', weight: 100, height: 20, width: 30, length: 40, cost: 500, tax: 50, version: 1, description: 'Description', id: $productId);
        $this->productRepository->save(product: $product);

        $cart = new Cart(userId: $userId);
        $this->cartRepository->saveCart(userId: $userId, cart: $cart);

        $command = new AddToCartCommand(userId: $userId, productId: $productId, quantity: 3);

        // Act
        $this->handler->handle(command: $command);

        // Assert
        $updatedCart = $this->cartRepository->getCartForUser(userId: $userId);
        self::assertCount(1, $updatedCart->getItems());
        self::assertEquals(3, $updatedCart->getTotalQuantity());
    }

    public function testHandleProductNotFound(): void
    {
        // Arrange
        $userId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $command = new AddToCartCommand(userId: $userId, productId: $productId, quantity: 3);

        // Assert
        $this->expectException(exception: Exception::class);
        $this->expectExceptionMessage(message: 'Товар не найден');

        // Act
        $this->handler->handle(command: $command);
    }
}
