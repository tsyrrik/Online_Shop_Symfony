<?php
namespace Tests\Unit\Application\Command;

use App\Application\Command\AddToCartCommand;
use PHPUnit\Framework\TestCase;

class AddToCartCommandTest extends TestCase
{
    public function testCommandInitialization()
    {
        $command = new AddToCartCommand(1, 2, 3);
        $this->assertEquals(1, $command->getUserId());
        $this->assertEquals(2, $command->getProductId());
        $this->assertEquals(3, $command->getQuantity());
    }
}