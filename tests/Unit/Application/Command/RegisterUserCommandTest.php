<?php
namespace Tests\Unit\Application\Command;

use App\Application\Command\RegisterUserCommand;
use PHPUnit\Framework\TestCase;

class RegisterUserCommandTest extends TestCase
{
    public function testCommandInitialization()
    {
        $command = new RegisterUserCommand('Johny Depp','+79141001212', 'capitaneSparow@google.com');
        $this->assertEquals('Johny Depp', $command->getName());
        $this->assertEquals('+79141001212', $command->getPhone());
        $this->assertEquals('capitaneSparow@google.com', $command->getEmail());
    }

}