<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddToCartRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'User ID is required')]
        #[Assert\Type('integer', message: 'User ID must be an integer')]
        public readonly int $userId,
        #[Assert\NotBlank(message: 'Product ID is required')]
        #[Assert\Type('integer', message: 'Product ID must be an integer')]
        public readonly int $productId,
        #[Assert\Positive(message: 'Quantity must be positive')]
        #[Assert\Type('integer', message: 'Quantity must be an integer')]
        public readonly int $quantity = 1,
    ) {}
}
