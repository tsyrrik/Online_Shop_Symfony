<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Cart;

use App\Domain\Cart\CartItem;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartItemTest extends TestCase
{
    public function testConstructorSetsPropertiesCorrectly(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $item = new CartItem(productId: $productId, quantity: 5);
        // Assert
        self::assertSame($productId, $item->getProductId());
        self::assertSame(5, $item->getQuantity());
    }

    public function testCanIncreaseQuantity(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        $item = new CartItem(productId: $productId, quantity: 5);
        // Act
        $item->increaseQuantity(amount: 3);
        // Assert
        self::assertSame(8, $item->getQuantity());
    }

    public function testCanDecreaseQuantity(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        // Act
        $item = new CartItem(productId: $productId, quantity: 5);
        // Assert
        $item->decreaseQuantity(amount: 2);
        self::assertSame(3, $item->getQuantity());
    }

    public function testDecreasingQuantityBelowZeroThrowsException(): void
    {
        // Arrange
        $productId = Uuid::uuid4();
        // Act
        $item = new CartItem(productId: $productId, quantity: 1);
        // Assert
        $this->expectException(exception: DomainException::class);
        $this->expectExceptionMessage(message: 'Quantity cannot be less than zero.');
        $item->decreaseQuantity(amount: 2);
    }
}
