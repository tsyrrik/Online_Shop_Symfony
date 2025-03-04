<?php

namespace App\Tests\Domain\Product;

use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreationWithAllFields(): void
    {
        // Arrange
        $product = new Product(
            name: 'Test Product',
            weight: 100,
            height: 10,
            width: 20,
            length: 30,
            cost: 500,
            tax: 50,
            version: 1,
            description: 'A test product'
        );

        // Assert
        $this->assertNull(actual: $product->getId());
        $this->assertSame(expected: 'Test Product', actual: $product->getName());
        $this->assertSame(expected: 100, actual: $product->getWeight());
        $this->assertSame(expected: 10, actual: $product->getHeight());
        $this->assertSame(expected: 20, actual: $product->getWidth());
        $this->assertSame(expected: 30, actual: $product->getLength());
        $this->assertSame(expected: 500, actual: $product->getCost());
        $this->assertSame(expected: 50, actual: $product->getTax());
        $this->assertSame(expected: 1, actual: $product->getVersion());
        $this->assertSame(expected: 'A test product', actual: $product->getDescription());
    }

    public function testProductCreationWithoutDescription(): void
    {
        // Arrange
        $product = new Product(
            name: 'No Desc Product',
            weight: 200,
            height: 15,
            width: 25,
            length: 35,
            cost: 1000,
            tax: 100,
            version: 1,
            description: null
        );

        // Assert
        $this->assertNull(actual: $product->getDescription());
        $this->assertSame(expected: 'No Desc Product', actual: $product->getName());
        $this->assertSame(expected: 200, actual: $product->getWeight());
    }
}