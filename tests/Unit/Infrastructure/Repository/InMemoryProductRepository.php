<?php

namespace Tests\Unit\Infrastructure\Repository;

use App\Infrastructure\Repository\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    public function testFindProduct()
    {
        $repository = new InMemoryProductRepository();
        $product = $repository->find(id: 1);

        $this->assertNotNull(actual: $product);
        $this->assertEquals(expected: 'Велосипед_10', actual: $product->getName());
        $this->assertNull(actual: $repository->find(id: 999));
    }
}