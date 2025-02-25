<?php
namespace Tests\Unit\Application\Command;

use App\Application\Command\RegisterUserCommand;
use PHPUnit\Framework\TestCase;

class RegisterUserCommandTest extends TestCase
{
    public function testCommandInitialization()
    {
        $command = new RegisterUserCommand('Johny Depp', 'capitaneSparow@google.com', '+79141001212');
        $this->assertEquals('Johny Depp', $command->getName());
        $this->assertEquals('capitaneSparow@google.com', $command->getEmail());
        $this->assertEquals('+79141001212', $command->getPhone());
    }

}