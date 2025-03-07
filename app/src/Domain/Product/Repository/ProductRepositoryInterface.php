<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;
use Ramsey\Uuid\UuidInterface;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function find(UuidInterface $id): ?Product;

    public function findById(UuidInterface $id): ?Product;

    /** @return Product[] */
    public function findAll(): array;
}
