<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Product\Repository\ProductRepositoryInterface;

final class ProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }
}
