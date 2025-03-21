<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[OA\Schema(schema: 'OrderItem', title: 'OrderItem', description: 'Details of an order item')]
class OrderItemDTO extends BaseDTO
{
    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440002')]
    #[Groups(['order:read'])]
    public string $productId;

    #[OA\Property(type: 'string', example: 'Product Name')]
    #[Groups(['order:read'])]
    public string $productName;

    #[OA\Property(type: 'integer', example: 2)]
    #[Groups(['order:read'])]
    public int $quantity;

    #[OA\Property(type: 'integer', example: 1000)]
    #[Groups(['order:read'])]
    public int $priceAtPurchase;

    public function __construct(string $productId, string $productName, int $quantity, int $priceAtPurchase)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->priceAtPurchase = $priceAtPurchase;
    }
}
