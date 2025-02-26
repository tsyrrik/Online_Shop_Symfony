<?php

namespace Tests\Unit\Infrastructure\Repository;

use App\Infrastructure\Repository\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    public function testFindProduct()
    {
        $repository = new InMemoryProductRepository();
        $product = $repository->find(1);

        $this->assertNotNull($product);
        $this->assertEquals('Велосипед_10', $product->getName());
        $this->assertNull($repository->find(999));
    }
}