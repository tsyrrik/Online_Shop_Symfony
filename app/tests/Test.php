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
            'Test Product',
            100,
            10,
            20,
            30,
            500,
            50,
            1,
            'Description',
        );
        self::assertNull($product->getId());
    }
}
