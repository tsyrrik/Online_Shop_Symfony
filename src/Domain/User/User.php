<?php

declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(type: "string")]
        private string $name,

        #[ORM\Column(type: "string")]
        private string $phone,

        #[ORM\Column(type: "string", unique: true)]
        private string $email
    ) {}

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
}
