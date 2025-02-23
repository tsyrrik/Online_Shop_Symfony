<?php

namespace App\Domain\Product;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private string $name,

        #[ORM\Column(type: Types::INTEGER)]
        private int $weight,

        #[ORM\Column(type: Types::INTEGER)]
        private int $height,

        #[ORM\Column(type: Types::INTEGER)]
        private int $width,

        #[ORM\Column(type: Types::INTEGER)]
        private int $length,

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description = null,

        #[ORM\Column(type: Types::INTEGER)]
        private int $cost,

        #[ORM\Column(type: Types::INTEGER)]
        private int $tax,

        #[ORM\Column(type: Types::INTEGER)]
        private int $version
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
