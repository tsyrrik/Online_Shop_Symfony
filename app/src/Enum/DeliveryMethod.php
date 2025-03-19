<?php

declare(strict_types=1);

namespace App\Enum;

enum DeliveryMethod: string
{
    case COURIER = 'courier';
    case SELF_DELIVERY = 'selfdelivery';
}
