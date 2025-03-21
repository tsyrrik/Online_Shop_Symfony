<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'AddToCartRequest',
    title: 'AddToCartRequest',
    description: 'Request to add product to cart',
)]
final readonly class AddToCartRequest
{
    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000')]
    #[Assert\NotBlank(message: 'User ID is required')]
    #[Assert\Uuid(message: 'User ID must be a valid UUID')]
    public readonly string $userId;

    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440001')]
    #[Assert\NotBlank(message: 'Product ID is required')]
    #[Assert\Uuid(message: 'Product ID must be a valid UUID')]
    public readonly string $productId;

    #[OA\Property(type: 'integer', example: 1, minimum: 1)]
    #[Assert\Positive(message: 'Quantity must be positive')]
    #[Assert\Type('integer', message: 'Quantity must be an integer')]
    public readonly int $quantity;

    public function __construct(
        string $userId,
        string $productId,
        int $quantity = 1,
    ) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
