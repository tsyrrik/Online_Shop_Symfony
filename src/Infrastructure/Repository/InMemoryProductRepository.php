<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use InvalidArgumentException;
use Override;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    /** @var array<int, Product> */
    private array $products = [];

    #[Override]
    public function save(Product $product): void
    {
        $id = $product->getId();
        if ($id === null) {
            throw new InvalidArgumentException(message: 'Product ID cannot be null');
        }
        $this->products[$id] = $product;
    }

    #[Override]
    public function find(int $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    #[Override]
    public function findById(int $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    #[Override]
    public function findAll(): array
    {
        return array_values(array: $this->products);
    }
}
