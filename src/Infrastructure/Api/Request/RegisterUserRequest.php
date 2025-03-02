<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegisterUserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Email cannot be empty')]
        #[Assert\Email(message: 'Invalid email address')]
        public readonly string $email,
        #[Assert\NotBlank(message: 'Name cannot be empty')]
        #[Assert\Regex(pattern: '/^[a-zA-Zа-яА-Я\\s]+$/u', message: 'Name must contain only letters and spaces')]
        public readonly string $name,
        #[Assert\NotBlank(message: 'Phone cannot be empty')]
        #[Assert\Regex(pattern: '/^\\+7\\d{9,10}$/', message: 'Phone must start with +7 followed by 9-10 digits')]
        public readonly string $phone,
    ) {}
}
