<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use InvalidArgumentException;

final class InMemoryProductRepository implements ProductRepositoryInterface
{
    /** @var array<string, Product> */
    private array $products = [];

    public function save(Product $product): void
    {
        $id = $product->getId();
        if ($id === null) { // Valid with ?UuidV7
            throw new InvalidArgumentException('Product ID cannot be null');
        }
        $this->products[$id->toString()] = $product;
    }

    public function find(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function findById(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->products);
    }
}
