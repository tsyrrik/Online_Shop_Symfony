<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RegisterUserCommand;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

readonly class RegisterUserHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = new User($command->getName(), $command->getEmail(), $command->getPhone());
        $this->userRepository->save($user);
    }
}
