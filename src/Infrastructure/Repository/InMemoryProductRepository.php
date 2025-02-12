<?php

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
            1 => new Product(1, 'Велосипед_10', 'Описание велосипеда', 500, 50, [
                'weight' => 15,
                'height' => 100,
                'width'  => 50,
                'lenght' => 180,
            ]),
            2 => new Product(2, 'Скейтборд_5', 'Описание скейтборда', 200, 20, [
                'weight' => 5,
                'height' => 30,
                'width'  => 20,
                'lenght' => 80,
            ]),
        ];
    }
    public function save(Product $product): void
    {
        $this->products[$product->getId()] = $product;
    }
    public function findById(int $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->products);
    }
}
