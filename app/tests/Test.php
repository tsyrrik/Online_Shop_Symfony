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
            name: 'name',
            weight: 1,
            height: 1,
            width: 1,
            length: 1,
            cost: 1,
            tax: 1,
            version: 1,
            description: 'description',
        );
        self::assertInstanceOf(Product::class, $product);
    }
}
