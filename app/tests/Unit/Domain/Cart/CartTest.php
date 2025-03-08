<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Product\Product;
use Doctrine\Common\Collections\Collection;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartTest extends TestCase
{
    private Cart $cart;

    protected function setUp(): void
    {
        // Arrange
        $this->cart = new Cart(Uuid::uuid4());
    }

    public function testCartIsInitiallyEmpty(): void
    {
        // Assert
        self::assertInstanceOf(Collection::class, $this->cart->getItems());
        self::assertCount(0, $this->cart->getItems());
        self::assertSame(0, $this->cart->getTotalQuantity());
    }

    public function testCanAddItemToCart(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $item = new CartItem($productId, 2);
        // Act
        $this->cart->addItem($item);
        $items = $this->cart->getItems();
        // Assert
        self::assertCount(1, $items);
        self::assertSame($item, $items->first());
        self::assertSame(2, $this->cart->getTotalQuantity());
    }

    public function testAddingItemWithSameProductIdIncreasesQuantity(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $item1 = new CartItem($productId, 2);
        $item2 = new CartItem($productId, 3);
        // Act
        $this->cart->addItem($item1);
        $this->cart->addItem($item2);
        $items = $this->cart->getItems();
        // Assert
        self::assertCount(1, $items);
        self::assertSame(5, $items->first()->getQuantity());
        self::assertSame(5, $this->cart->getTotalQuantity());
    }

    public function testCannotAddMoreThan20Items(): void
    {
        // Arrange
        for ($i = 1; $i <= 20; ++$i) {
            $this->cart->addItem(new CartItem(Uuid::uuid4(), 1));
        }
        // Act
        self::assertCount(20, $this->cart->getItems());
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('There cannot be more than 20 items in an order');
        $this->cart->addItem(new CartItem(Uuid::uuid4(), 1));
    }

    public function testCanRemoveItemFromCart(): void
    {
        // Arrange
        $productId1 = Uuid::uuid4();
        $productId2 = Uuid::uuid4();
        $item1 = new CartItem($productId1, 2);
        $item2 = new CartItem($productId2, 3);
        $this->cart->addItem($item1);
        $this->cart->addItem($item2);
        // Act
        $this->cart->removeItem($productId1);
        $items = $this->cart->getItems();
        // Assert
        self::assertCount(1, $items);
        self::assertFalse($items->contains($item1));
        self::assertTrue($items->contains($item2));
        self::assertSame(3, $this->cart->getTotalQuantity());
    }

    public function testRemovingNonExistentItemDoesNothing(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $item = new CartItem($productId, 2);
        $this->cart->addItem($item);
        // Act
        $this->cart->removeItem(Uuid::uuid4());
        $items = $this->cart->getItems();
        // Assert
        self::assertCount(1, $items);
        self::assertTrue($items->contains($item));
        self::assertSame(2, $this->cart->getTotalQuantity());
    }

    public function testAnAddProductToCart(): void
    {
        $productId = Uuid::uuid4();
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, $productId);
        $this->cart->addProduct($product, 2);
        $items = $this->cart->getItems();
        self::assertCount(1, $items);
        $item = $items->first();
        self::assertSame($productId, $item->getProductId());
        self::assertSame(2, $item->getQuantity());
        self::assertSame(2, $this->cart->getTotalQuantity());
    }

    public function testAddingProductWithExistingProductIncreasesQuantity(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, $productId);
        // Act
        $this->cart->addProduct($product, 2);
        $this->cart->addProduct($product, 3);
        $items = $this->cart->getItems();
        // Assert
        self::assertCount(1, $items);
        self::assertSame(5, $items->first()->getQuantity());
        self::assertSame(5, $this->cart->getTotalQuantity());
    }

    public function testAddProductWithNullIdThrowsException(): void
    {
        // Arrange
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, null);
        // Act
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Product ID cannot be null');
        $this->cart->addProduct($product, 1);
    }

    public function testAddProductWithInvalidQuantityThrowsException(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, $productId);
        // Act
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be greater than zero');
        $this->cart->addProduct($product, 0);
    }

    public function testUserIdIsSetCorrectly(): void
    {
        // Arrange
        $userId = Uuid::uuid4();
        // Act
        $cart = new Cart($userId);
        // Assert
        self::assertSame($userId, $cart->getUserId());
    }
}
