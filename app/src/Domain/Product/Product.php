<?php

declare(strict_types=1);

namespace App\Domain\Product;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private ?UuidInterface $id = null;

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
        #[ORM\Column(type: Types::INTEGER)]
        private int $cost,
        #[ORM\Column(type: Types::INTEGER)]
        private int $tax,
        #[ORM\Column(type: Types::INTEGER)]
        private int $version,
        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description = null,
        ?UuidInterface $id = null,
    ) {
        $this->id = $id;
    }

    public function getId(): ?UuidInterface
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
