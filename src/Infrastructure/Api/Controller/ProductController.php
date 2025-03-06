<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService) {}

    #[Route(path: '/api/products', name: 'api_products', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'cost' => $product->getCost(),
                'tax' => $product->getTax(),
                'measurements' => [
                    'weight' => $product->getWeight(),
                    'height' => $product->getHeight(),
                    'width' => $product->getWidth(),
                    'length' => $product->getLength(),
                ],
            ];
        }

        return new JsonResponse(data: $data);
    }
}
