<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(int $id): ?Product;

    /**
     * Возвращает список всех товаров.
     *
     * @return Product[]
     */
    public function findAll(): array;
}
