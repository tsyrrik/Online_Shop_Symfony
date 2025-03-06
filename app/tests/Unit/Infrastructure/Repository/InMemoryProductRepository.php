<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repository;

use App\Infrastructure\Repository\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepository extends TestCase
{
    public function testFindProductReturnsCorrectResult(): void
    {
        // Arrange
        $repository = new self();
        $existingProductId = 1;
        $nonExistentProductId = 999;

        // Act
        $product = $repository->find(id: $existingProductId);
        $nonExistentProduct = $repository->find(id: $nonExistentProductId);

        // Assert
        self::assertNotNull(actual: $product);
        self::assertEquals(expected: 'Велосипед_10', actual: $product->getName());
        self::assertNull(actual: $nonExistentProduct);
    }
}
