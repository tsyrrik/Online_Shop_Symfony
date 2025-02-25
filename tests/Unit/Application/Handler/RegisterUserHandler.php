<?php

namespace Tests\Unit\Application\Handler;

use App\Application\Command\RegisterUserCommand;
use App\Application\Handler\RegisterUserHandler;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RegisterUserHandlerTest extends TestCase
{
    private UserRepositoryInterface|MockObject $userRepository;
    private RegisterUserHandler $handler;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new RegisterUserHandler($this->userRepository);
    }

    public function testHandle()
    {
        $command = new RegisterUserCommand('John Doe', 'john@example.com', '+123456789');
        $this->userRepository->expects($this->once())->method('save')->with(
            $this->callback(function (User $user) {
                return $user->getName() === 'John Doe' &&
                    $user->getEmail() === 'john@example.com' &&
                    $user->getPhone() === '+123456789';
            })
        );

        $this->handler->__invoke($command);
    }
}