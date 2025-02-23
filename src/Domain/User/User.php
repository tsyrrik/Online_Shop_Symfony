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
