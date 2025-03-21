<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddToCartResponse',
    title: 'AddToCartResponse',
    description: 'Reply to adding product to cart',
)]
final class AddToCartResponseDTO extends BaseDTO
{
    #[OA\Property(type: 'string', example: 'Product added to cart')]
    public string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }
}
