<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Product\Product;
use App\Domain\ValueObject\UuidV7;
use Doctrine\Common\Collections\Collection;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    private Cart $cart;

    protected function setUp(): void
    {
        $this->cart = new Cart(new UuidV7());
    }

    public function testCartIsInitiallyEmpty(): void
    {
        self::assertInstanceOf(Collection::class, $this->cart->getItems());
        self::assertCount(0, $this->cart->getItems());
        self::assertSame(0, $this->cart->getTotalQuantity());
    }

    public function testCanAddItemToCart(): void
    {
        $productId = new UuidV7();
        $item = new CartItem($productId, 2);
        $this->cart->addItem($item);
        $items = $this->cart->getItems();
        self::assertCount(1, $items);
        self::assertSame($item, $items->first());
        self::assertSame(2, $this->cart->getTotalQuantity());
    }

    public function testAddingItemWithSameProductIdIncreasesQuantity(): void
    {
        $productId = new UuidV7();
        $item1 = new CartItem($productId, 2);
        $item2 = new CartItem($productId, 3);
        $this->cart->addItem($item1);
        $this->cart->addItem($item2);
        $items = $this->cart->getItems();
        self::assertCount(1, $items);
        self::assertSame(5, $items->first()->getQuantity());
        self::assertSame(5, $this->cart->getTotalQuantity());
    }

    public function testCannotAddMoreThan20Items(): void
    {
        for ($i = 1; $i <= 20; ++$i) {
            $this->cart->addItem(new CartItem(new UuidV7(), 1));
        }
        self::assertCount(20, $this->cart->getItems());
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('There cannot be more than 20 items in an order');
        $this->cart->addItem(new CartItem(new UuidV7(), 1));
    }

    public function testCanRemoveItemFromCart(): void
    {
        $productId1 = new UuidV7();
        $productId2 = new UuidV7();
        $item1 = new CartItem($productId1, 2);
        $item2 = new CartItem($productId2, 3);
        $this->cart->addItem($item1);
        $this->cart->addItem($item2);
        $this->cart->removeItem($productId1);
        $items = $this->cart->getItems();
        self::assertCount(1, $items);
        self::assertFalse($items->contains($item1));
        self::assertTrue($items->contains($item2));
        self::assertSame(3, $this->cart->getTotalQuantity());
    }

    public function testRemovingNonExistentItemDoesNothing(): void
    {
        $productId = new UuidV7();
        $item = new CartItem($productId, 2);
        $this->cart->addItem($item);
        $this->cart->removeItem(new UuidV7());
        $items = $this->cart->getItems();
        self::assertCount(1, $items);
        self::assertTrue($items->contains($item));
        self::assertSame(2, $this->cart->getTotalQuantity());
    }

    public function testAnAddProductToCart(): void
    {
        $productId = new UuidV7();
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
        $productId = new UuidV7();
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, $productId);
        $this->cart->addProduct($product, 2);
        $this->cart->addProduct($product, 3);
        $items = $this->cart->getItems();
        self::assertCount(1, $items);
        self::assertSame(5, $items->first()->getQuantity());
        self::assertSame(5, $this->cart->getTotalQuantity());
    }

    public function testAddProductWithNullIdThrowsException(): void
    {
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, null);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Product ID cannot be null');
        $this->cart->addProduct($product, 1);
    }

    public function testAddProductWithInvalidQuantityThrowsException(): void
    {
        $productId = new UuidV7();
        $product = new Product('Test Product', 100, 10, 20, 30, 1000, 10, 1, null, $productId);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be greater than zero');
        $this->cart->addProduct($product, 0);
    }

    public function testUserIdIsSetCorrectly(): void
    {
        $userId = new UuidV7();
        $cart = new Cart($userId);
        self::assertSame($userId, $cart->getUserId());
    }
}
