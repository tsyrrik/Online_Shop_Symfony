<?php

declare(strict_types=1);

namespace App\Enum;

enum OrderStatus: string
{
    case PAID = 'paid';
    case IN_ASSEMBLY = 'in_assembly';
    case READY_FOR_DELIVERY = 'ready_for_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PAID => 'Paid and awaiting assembly',
            self::IN_ASSEMBLY => 'In assembly',
            self::READY_FOR_DELIVERY => 'Ready for pickup/delivered',
            self::DELIVERED => 'Received',
            self::CANCELLED => 'Cancelled',
        };
    }
}
