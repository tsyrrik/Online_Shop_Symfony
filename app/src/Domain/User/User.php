<?php

declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private UuidInterface $id;

    public function __construct(
        #[Assert\NotBlank(message: 'Name cannot be empty')]
        #[Assert\Regex(pattern: '/^[a-zA-Zа-яА-Я\s]+$/u', message: 'Invalid name provided')]
        #[ORM\Column(type: Types::STRING)]
        private string $name,
        #[Assert\Regex(pattern: '/^\+7\d{9,10}$/', message: 'Invalid phone number provided')]
        #[ORM\Column(type: Types::STRING)]
        private string $phone,
        #[Assert\Email(message: 'Invalid email address provided')]
        #[ORM\Column(type: Types::STRING, unique: true)]
        private string $email,
    ) {
        $this->validateName(name: $name);
        $this->validatePhone(phone: $phone);
        $this->validateEmail(email: $email);
    }

    public function getId(): UuidInterface
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
