<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function find(string $id): ?Product;

    public function findById(string $id): ?Product;

    /** @return Product[] */
    public function findAll(): array;
}
