<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddToCartRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'User ID is required')]
        #[Assert\Uuid(message: 'User ID must be a valid UUID')]
        public readonly string $userId,
        #[Assert\NotBlank(message: 'Product ID is required')]
        #[Assert\Uuid(message: 'Product ID must be a valid UUID')]
        public readonly string $productId,
        #[Assert\Positive(message: 'Quantity must be positive')]
        #[Assert\Type('integer', message: 'Quantity must be an integer')]
        public readonly int $quantity = 1,
    ) {}
}
