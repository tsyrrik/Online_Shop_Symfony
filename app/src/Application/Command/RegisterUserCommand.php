<?php

declare(strict_types=1);

namespace App\Application\Command;

readonly class RegisterUserCommand
{
    public function __construct(private string $name, private string $phone, private string $email) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
