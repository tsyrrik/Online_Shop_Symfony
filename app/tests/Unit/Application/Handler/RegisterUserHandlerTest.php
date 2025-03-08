<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handler;

use App\Application\Command\RegisterUserCommand;
use App\Application\Handler\RegisterUserHandler;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

class RegisterUserHandlerTest extends TestCase
{
    private UserRepositoryInterface $userRepository;

    private RegisterUserHandler $handler;

    protected function setUp(): void
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->handler = new RegisterUserHandler(userRepository: $this->userRepository);
    }

    public function testHandle(): void
    {
        $command = new RegisterUserCommand(name: 'Johny Depp', phone: '+79991231234', email: 'capitaneSparow@gmail.com');

        $this->handler->__invoke(command: $command);

        $savedUser = $this->userRepository->findByEmail('capitaneSparow@gmail.com');

        self::assertNotNull($savedUser);
        self::assertSame('Johny Depp', $savedUser->getName());
        self::assertSame('+79991231234', $savedUser->getPhone());
        self::assertSame('capitaneSparow@gmail.com', $savedUser->getEmail());
    }
}
