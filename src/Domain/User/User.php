<?php

declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: "users")]
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
        private string $email
    ) {
        $this->validateName($name);
        $this->validatePhone($phone);
        $this->validateEmail($email);
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
        if (empty($name) || !preg_match('/^[a-zA-Zа-яА-Я\s]+$/u', $name)) {
            throw new \InvalidArgumentException('Invalid name provided');
        }
    }


    private function validatePhone(string $phone): void
    {
        $pattern = '/^\+7\d{10}$/';
        if (!preg_match($pattern, $phone)) {
            throw new \InvalidArgumentException('Invalid phone number provided');
        }
    }


    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address provided');
        }
    }

}
