<?php

declare(strict_types=1);

namespace App\Tests\Domain\Product;

use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductTest extends TestCase
{
    public function testProductCreationWithAllFields(): void
    {
        // Arrange
        $id = Uuid::uuid4();
        $product = new Product('Test Product', 100, 10, 20, 30, 500, 50, 1, 'A test product', $id);

        // Assert
        self::assertSame($id, $product->getId());
        self::assertSame('Test Product', $product->getName());
        self::assertSame(100, $product->getWeight());
        self::assertSame(10, $product->getHeight());
        self::assertSame(20, $product->getWidth());
        self::assertSame(30, $product->getLength());
        self::assertSame(500, $product->getCost());
        self::assertSame(50, $product->getTax());
        self::assertSame(1, $product->getVersion());
        self::assertSame('A test product', $product->getDescription());
    }

    public function testProductCreationWithoutDescription(): void
    {
        // Arrange
        $id = Uuid::uuid4();
        $product = new Product('No Desc Product', 200, 15, 25, 35, 1000, 100, 1, null, $id);

        // Assert
        self::assertSame($id, $product->getId());
        self::assertNull($product->getDescription());
        self::assertSame('No Desc Product', $product->getName());
        self::assertSame(200, $product->getWeight());
    }
}
