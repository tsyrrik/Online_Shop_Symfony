<?php


declare(strict_types=1);

namespace App\Tests\Unit\Domain\Cart;

use App\Domain\Cart\CartItem;
use DomainException;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testConstructorSetsPropertiesCorrectly(): void
    {
        // Arrange
        $item = new CartItem(productId: 1, quantity: 5);
        // Assert
        self::assertSame(expected: 1, actual: $item->getProductId());
        self::assertSame(expected: 5, actual: $item->getQuantity());
    }

    public function testCanIncreaseQuantity(): void
    {
        // Arrange
        $item = new CartItem(productId: 1, quantity: 5);
        // Act
        $item->increaseQuantity(amount: 3);
        // Assert
        self::assertSame(expected: 8, actual: $item->getQuantity());
    }

    public function testCanDecreaseQuantity(): void
    {
        // Arrange
        $item = new CartItem(productId: 1, quantity: 5);
        // Act
        $item->decreaseQuantity(amount: 2);
        // Assert
        self::assertSame(expected: 3, actual: $item->getQuantity());
    }

    public function testDecreasingQuantityBelowZeroThrowsException(): void
    {
        // Arrange
        $item = new CartItem(productId: 1, quantity: 1);
        // Act & Assert
        $this->expectException(exception: DomainException::class);
        $this->expectExceptionMessage(message: 'Quantity cannot be less than zero.');
        $item->decreaseQuantity(amount: 2);
    }
}
