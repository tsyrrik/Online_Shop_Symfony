<?php

namespace App\Application\Command;

readonly class RegisterUserCommand
{
    public function __construct(private string $name, private string $email, private string $phone) {}

    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): string { return $this->phone; }
}