<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Product\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Test extends KernelTestCase
{
    public function test(): void
    {
        self::assertTrue(true);
    }

    public function testProduct(): void
    {
        $product = new Product(
            name: 'Test Product',
            weight: 100,
            height: 10,
            width: 20,
            length: 30,
            cost: 500,
            tax: 50,
            version: 1,
            description: 'Description',
        );
        self::assertNull($product->getId());
    }
}
