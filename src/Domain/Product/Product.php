<?php

namespace App\Domain\Product;

class Product
{
    private int $id;
    private string $name;
    private ?string $description;
    private string $cost;
    private string $tax;
    private int $version;
    private array $measurments;

    public function __construct(int $id, string $name, string $description, string $cost, string $tax, int $version, array $measurments)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->cost = $cost;
        $this->tax = $tax;
        $this->version = $version;
        $this->measurments = $measurments;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getCost(): string
    {
        return $this->cost;
    }
    public function getTax(): string
    {
        return $this->tax;
    }
    public function getVersion(): int
    {
        return $this->version;
    }
    public function getMeasurments(): array
    {
        return $this->measurments;
    }
}
