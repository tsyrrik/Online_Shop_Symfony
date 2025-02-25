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
        $product = new Product('Test Product', 10, 20, 30, 40, 'Description', 100, 10, 1);
        $this->productRepository->method('findById')->with(2)->willReturn($product);

        $cart = new Cart(1);
        $this->cartRepository->method('getCartForUser')->with(1)->willReturn($cart);

        $command = new AddToCartCommand(1, 2, 3);
        $this->handler->handle($command);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(3, $cart->getTotalQuantity());
        $this->cartRepository->expects($this->once())->method('saveCart')->with(1, $cart);
    }

    public function testHandleProductNotFound()
    {
        $this->productRepository->method('findById')->with(2)->willReturn(null);

        $command = new AddToCartCommand(1, 2, 3);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Товар не найден');
        $this->handler->handle($command);
    }
}