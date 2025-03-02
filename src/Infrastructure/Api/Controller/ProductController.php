<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Domain\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    #[Route(
        path: '/api/products',
        name: 'api_products',
        methods: ['GET']
    )]
    public function list(): JsonResponse
    {
        $products = $this->productRepository->findAll();

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'cost' => $product->getCost(),
                'tax' => $product->getTax(),
                'measurements' => $product->getMeasurements(),
            ];
        }

        return new JsonResponse($data);
    }
}
