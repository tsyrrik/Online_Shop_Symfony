<?php
namespace Tests\Unit\Application\Command;

use App\Application\Command\RegisterUserCommand;
use PHPUnit\Framework\TestCase;

class RegisterUserCommandTest extends TestCase
{
    public function testCommandInitialization()
    {
        $command = new RegisterUserCommand('John Doe', 'john@example.com', '+123456789');
        $this->assertEquals('John Doe', $command->getName());
        $this->assertEquals('john@example.com', $command->getEmail());
        $this->assertEquals('+123456789', $command->getPhone());
    }
}