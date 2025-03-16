<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\ValueObject\UuidV7;

final readonly class CheckoutCommand
{
    public function __construct(
        public UuidV7 $userId,
        public string $deliveryMethod,
        public string $orderPhone,
    ) {}
}
