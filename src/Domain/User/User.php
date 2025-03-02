<?php

declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private string $name,
        #[ORM\Column(type: Types::STRING)]
        private string $phone,
        #[ORM\Column(type: Types::STRING, unique: true)]
        private string $email,
    ) {
        //        dd($name, $phone, $email);
        $this->validateName(name: $name);
        $this->validatePhone(phone: $phone);
        $this->validateEmail(email: $email);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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

    private function validateName(string $name): void
    {
        if (empty($name) || !preg_match(pattern: '/^[a-zA-Zа-яА-Я\s]+$/u', subject: $name)) {
            throw new InvalidArgumentException(message: 'Invalid name provided');
        }
    }

    private function validatePhone(string $phone): void
    {
        $pattern = '/^\+7\d{9,10}$/';
        if (!preg_match(pattern: $pattern, subject: $phone)) {
            throw new InvalidArgumentException(message: 'Invalid phone number provided');
        }
    }

    private function validateEmail(string $email): void
    {
        if (!filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(message: 'Invalid email address provided');
        }
    }
}
