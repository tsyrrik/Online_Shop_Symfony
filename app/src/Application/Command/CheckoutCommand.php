<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\ValueObject\UuidV7;
use App\Enum\DeliveryMethod;

final readonly class CheckoutCommand
{
    public function __construct(
        public UuidV7 $userId,
        public DeliveryMethod $deliveryMethod,
        public string $orderPhone,
    ) {}
}
