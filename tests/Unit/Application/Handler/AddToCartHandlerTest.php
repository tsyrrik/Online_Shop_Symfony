<?php

namespace Tests\Unit\Application\Handler;

use App\Application\Command\AddToCartCommand;
use App\Application\Handler\AddToCartHandler;
use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class AddToCartHandlerTest extends TestCase
{
    private ProductRepositoryInterface|MockObject $productRepository;
    private CartRepositoryInterface|MockObject $cartRepository;
    private AddToCartHandler $handler;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->handler = new AddToCartHandler($this->productRepository, $this->cartRepository);
    }

    public function testHandleWithExistingCart()
    {
        $product = new Product('Test Product', 100, 20, 30, 40, 500, 50, 1, 'Description');
        $reflection = new \ReflectionClass($product);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($product, 2);

        $this->productRepository
            ->method('findById')
            ->with(2)
            ->willReturn($product);

        $cart = new Cart(1);

        $this->cartRepository
            ->method('getCartForUser')
            ->with(1)
            ->willReturn($cart);

        $this->cartRepository
            ->expects($this->once())
            ->method('saveCart')
            ->with(1, $this->callback(function (Cart $savedCart) use ($cart) {
                return $savedCart === $cart && $savedCart->getTotalQuantity() === 3;
            }));

        $command = new AddToCartCommand(1, 2, 3);
        $this->handler->handle($command);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(3, $cart->getTotalQuantity());
    }

    public function testHandleProductNotFound()
    {
        $this->productRepository
            ->method('findById')
            ->with(2)
            ->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Товар не найден');

        $command = new AddToCartCommand(1, 2, 3);
        $this->handler->handle($command);
    }
}
