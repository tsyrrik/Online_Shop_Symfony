<?php
declare(strict_types=1);

namespace App\Tests;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Test extends KernelTestCase
{
    public function test(): void
    {
        self::assertTrue(true);
    }
}