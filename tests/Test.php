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
            'name',
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            'description',
        );
        self::assertInstanceOf(Product::class, $product);
    }
}
