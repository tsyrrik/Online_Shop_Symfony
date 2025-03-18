<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\ValueObject\UuidV7;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV7 $id;

    #[Assert\NotBlank(message: 'Name cannot be empty')]
    #[Assert\Regex(pattern: '/^[a-zA-Zа-яА-Я\s]+$/u', message: 'Invalid name provided')]
    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[Assert\Regex(pattern: '/^\+7\d{9,10}$/', message: 'Invalid phone number provided')]
    #[ORM\Column(type: Types::STRING)]
    private string $phone;

    #[Assert\Email(message: 'Invalid email address provided')]
    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    private string $role = 'ROLE_USER';

    public function __construct(
        ?UuidV7 $id,
        string $name,
        string $phone,
        string $email,
        string $role = 'ROLE_USER',
    ) {
        $this->id = $id ?? new UuidV7();
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->role = $role;
    }

    public function getId(): UuidV7
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

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
