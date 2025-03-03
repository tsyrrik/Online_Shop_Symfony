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

class CartTest extends TestCase
{
    private Cart $cart;

    protected function setUp(): void
    {
        // Arrange
        $this->cart = new Cart(userId: 1);
    }

    private function createProductWithId(int $id): Product
    {
        $product = new Product(
            name: 'Test Product',
            weight: 100,
            height: 10,
            width: 20,
            length: 30,
            cost: 1000,
            tax: 10,
            version: 1
        );

        $reflection = new \ReflectionClass(objectOrClass: $product);
        $property = $reflection->getProperty(name: 'id');
        $property->setAccessible(accessible: true);
        $property->setValue(objectOrValue: $product, value: $id);

        return $product;
    }

    public function testCartIsInitiallyEmpty(): void
    {
        // Assert
        $this->assertInstanceOf(expected: Collection::class, actual: $this->cart->getItems());
        $this->assertCount(expectedCount: 0, haystack: $this->cart->getItems());
        $this->assertSame(expected: 0, actual: $this->cart->getTotalQuantity());
    }

    public function testCanAddItemToCart(): void
    {
        // Arrange
        $item = new CartItem(productId: 1, quantity: 2);
        // Act
        $this->cart->addItem(item: $item);
        $items = $this->cart->getItems();
        // Assert
        $this->assertCount(expectedCount: 1, haystack: $items);
        $this->assertSame(expected: $item, actual: $items->first());
        $this->assertSame(expected: 2, actual: $this->cart->getTotalQuantity());
    }

    public function testAddingItemWithSameProductIdIncreasesQuantity(): void
    {
        // Arrange
        $item1 = new CartItem(productId: 1, quantity: 2);
        $item2 = new CartItem(productId: 1, quantity: 3);
        // Act
        $this->cart->addItem(item: $item1);
        $this->cart->addItem(item: $item2);
        $items = $this->cart->getItems();
        // Assert
        $this->assertCount(expectedCount: 1, haystack: $items);
        $this->assertSame(expected: 5, actual: $items->first()->getQuantity());
        $this->assertSame(expected: 5, actual: $this->cart->getTotalQuantity());
    }

    public function testCannotAddMoreThan20Items(): void
    {
        // Arrange
        for ($i = 1; $i <= 20; $i++) {
            $this->cart->addItem(item: new CartItem(productId: $i, quantity: 1));
        }
        // Act
        $this->assertCount(expectedCount: 20, haystack: $this->cart->getItems());
        $this->expectException(exception: DomainException::class);
        $this->expectExceptionMessage(message: 'There cannot be more than 20 items in an order');
        $this->cart->addItem(item: new CartItem(productId: 21, quantity: 1));
    }

    public function testCanRemoveItemFromCart(): void
    {
        // Arrange
        $item1 = new CartItem(productId: 1, quantity: 2);
        $item2 = new CartItem(productId: 2, quantity: 3);
        $this->cart->addItem(item: $item1);
        $this->cart->addItem(item: $item2);
        $this->cart->removeItem(productId: 1);
        // Act
        $items = $this->cart->getItems();
        // Assert
        $this->assertCount(expectedCount: 1, haystack: $items);
        $this->assertFalse(condition: $items->contains($item1));
        $this->assertTrue(condition: $items->contains($item2));
        $this->assertSame(expected: 3, actual: $this->cart->getTotalQuantity());
    }

    public function testRemovingNonExistentItemDoesNothing(): void
    {
        // Arrange
        $item = new CartItem(productId: 1, quantity: 2);
        $this->cart->addItem(item: $item);
        // Act
        $this->cart->removeItem(productId: 999);
        $items = $this->cart->getItems();
        // Assert
        $this->assertCount(expectedCount: 1, haystack: $items);
        $this->assertTrue(condition: $items->contains($item));
        $this->assertSame(expected: 2, actual: $this->cart->getTotalQuantity());
    }

    public function testAnAddProductToCart(): void
    {
        // Arrange
        $product = $this->createProductWithId(id: 1);
        // Act
        $this->cart->addProduct(product: $product, quantity: 2);
        $items = $this->cart->getItems();
        // Assert
        $this->assertCount(expectedCount: 1, haystack: $items);
        $item = $items->first();
        $this->assertSame(expected: 1, actual: $item->getProductId());
        $this->assertSame(expected: 2, actual: $item->getQuantity());
        $this->assertSame(expected: 2, actual: $this->cart->getTotalQuantity());
    }

    public function testAddingProductWithExistingProductIncreasesQuantity(): void
    {
        // Arrange
        $product = $this->createProductWithId(id: 1); // Используем продукт с id
        $this->cart->addProduct(product: $product, quantity: 2);
        // Act
        $this->cart->addProduct(product: $product, quantity: 3);
        $items = $this->cart->getItems();
        // Assert
        $this->assertCount(expectedCount: 1, haystack: $items);
        $this->assertSame(expected: 5, actual: $items->first()->getQuantity());
        $this->assertSame(expected: 5, actual: $this->cart->getTotalQuantity());
    }

    public function testAddProductWithNullIdThrowsException(): void
    {
        // Arrange
        $product = new Product(
            name: 'Test Product',
            weight: 100,
            height: 10,
            width: 20,
            length: 30,
            cost: 1000,
            tax: 10,
            version: 1
        );
        // Act
        $this->expectException(exception: InvalidArgumentException::class);
        $this->expectExceptionMessage(message: 'Product ID cannot be null');
        $this->cart->addProduct(product: $product, quantity: 1);
    }

    public function testAddProductWithInvalidQuantityThrowsException(): void
    {
        // Arrange
        $product = $this->createProductWithId(id: 1);
        // Act
        $this->expectException(exception: InvalidArgumentException::class);
        $this->expectExceptionMessage(message: 'Quantity must be greater than zero');
        $this->cart->addProduct(product: $product, quantity: 0);
    }

    public function testUserIdIsSetCorrectly(): void
    {
        // Assert
        $this->assertSame(expected: 1, actual: $this->cart->getUserId());
    }
}