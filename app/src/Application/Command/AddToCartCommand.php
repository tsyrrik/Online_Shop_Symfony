<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\ValueObject\UuidV7;

final readonly class AddToCartCommand
{
    public function __construct(
        public UuidV7 $userId,
        public UuidV7 $productId,
        public int $quantity,
    ) {}
}
