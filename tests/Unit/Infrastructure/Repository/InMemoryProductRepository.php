<?php

namespace Tests\Unit\Infrastructure\Repository;

use App\Infrastructure\Repository\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function testFindProductReturnsCorrectResult(): void
    {
        // Arrange
        $repository = new InMemoryProductRepository();
        $existingProductId = 1;
        $nonExistentProductId = 999;

        // Act
        $product = $repository->find(id: $existingProductId);
        $nonExistentProduct = $repository->find(id: $nonExistentProductId);

        // Assert
        $this->assertNotNull(actual: $product);
        $this->assertEquals(expected: 'Велосипед_10', actual: $product->getName());
        $this->assertNull(actual: $nonExistentProduct);
    }
}