<?php

namespace App\Application\Handler;

use App\Application\Command\RegisterUserCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

#[AsMessageHandler]
readonly class RegisterUserHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = new User($command->getName(), $command->getPhone(), $command->getEmail());
        $this->userRepository->save($user);
    }
}
