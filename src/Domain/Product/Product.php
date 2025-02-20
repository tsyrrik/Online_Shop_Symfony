<?php

namespace App\Domain\Product;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(type: "string")]
        private string $name,

        #[ORM\Column(type: "integer")]
        private int $weight,

        #[ORM\Column(type: "integer")]
        private int $height,

        #[ORM\Column(type: "integer")]
        private int $width,

        #[ORM\Column(type: "integer")]
        private int $length,

        #[ORM\Column(type: "text", nullable: true)]
        private ?string $description = null,

        #[ORM\Column(type: "integer")]
        private int $cost,

        #[ORM\Column(type: "integer")]
        private int $tax,

        #[ORM\Column(type: "integer")]
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
