<?php

declare(strict_types=1);

namespace App\Application\Command;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $email,
    ) {}
}
