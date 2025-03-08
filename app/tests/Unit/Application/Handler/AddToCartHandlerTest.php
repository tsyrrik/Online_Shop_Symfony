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
        $this->handler = new AddToCartHandler($this->productRepository, $this->cartRepository);
    }

    public function testHandleWithExistingCart(): void
    {
        // Arrange
        $userId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $product = new Product('Test Product', 100, 20, 30, 40, 500, 50, 1, 'Description', $productId);
        $this->productRepository->save($product);

        $cart = new Cart($userId);
        $this->cartRepository->saveCart($userId, $cart);

        $command = new AddToCartCommand($userId, $productId, 3);

        // Act
        $this->handler->handle($command);

        // Assert
        $updatedCart = $this->cartRepository->getCartForUser($userId);
        self::assertCount(1, $updatedCart->getItems());
        self::assertEquals(3, $updatedCart->getTotalQuantity());
    }

    public function testHandleProductNotFound(): void
    {
        // Arrange
        $userId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $command = new AddToCartCommand($userId, $productId, 3);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Товар не найден');

        // Act
        $this->handler->handle($command);
    }
}
