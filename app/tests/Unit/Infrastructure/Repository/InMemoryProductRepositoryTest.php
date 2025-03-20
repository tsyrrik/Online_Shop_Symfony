<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repository;

use App\Domain\Product\Product;
use App\Domain\ValueObject\UuidV7;
use App\Infrastructure\Repository\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    public function testFindProductReturnsCorrectResult(): void
    {
        // Arrange
        $repository = new InMemoryProductRepository();
        $productId = new UuidV7();
        $product = new Product(
            name: 'Test Product',
            weight: 100,
            height: 10,
            width: 20,
            length: 30,
            cost: 1000,
            tax: 10,
            version: 1,
            description: null,
        );
        $product->setId(id: $productId);
        $repository->save(product: $product);

        // Act
        $result = $repository->find(id: $productId->toString());

        // Assert
        self::assertSame($product, $result);
    }
}
