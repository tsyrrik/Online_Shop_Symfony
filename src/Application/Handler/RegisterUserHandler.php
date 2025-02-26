<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RegisterUserCommand;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

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
