<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepositoryInterface;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Product[]
     */
    private array $products = [];

    public function __construct()
    {
        $this->products = [
            1 => new Product(
                name: 'Велосипед_10',
                weight: 15,
                height: 100,
                width: 50,
                length: 180,
                description: 'Описание велосипеда',
                cost: 500,
                tax: 50,
                version: 1,
            ),
            2 => new Product(
                name: 'Скейтборд_5',
                weight: 5,
                height: 30,
                width: 20,
                length: 80,
                description: 'Описание скейтборда',
                cost: 200,
                tax: 20,
                version: 1,
            ),
        ];
    }

    public function save(Product $product): void
    {
        $this->products[$product->getId()] = $product;
    }

    public function find(int $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function findById(int $id): ?Product
    {
        return $this->find(id: $id);
    }
}
