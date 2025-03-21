<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Service\ProductService;
use App\Infrastructure\Api\DTO\ProductDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService,
        private SerializerInterface $serializer,
    ) {}

    #[Route(path: '/api/products', name: 'api_products', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        $productDTOs = [];

        foreach ($products as $product) {
            $productDTOs[] = new ProductDTO(
                id: $product->getId()->toString(),
                name: $product->getName(),
                description: $product->getDescription(),
                cost: $product->getCost(),
                tax: $product->getTax(),
                measurements: [
                    'weight' => $product->getWeight(),
                    'height' => $product->getHeight(),
                    'width' => $product->getWidth(),
                    'length' => $product->getLength(),
                ],
            );
        }

        $json = $this->serializer->serialize($productDTOs, 'json', ['groups' => ['product:read']]);

        return new JsonResponse(data: $json);
    }
}
