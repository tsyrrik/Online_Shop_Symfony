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
            self::PAID => 'Оплачен и ждёт сборки',
            self::IN_ASSEMBLY => 'В сборке',
            self::READY_FOR_DELIVERY => 'Готов к выдаче/доставляется',
            self::DELIVERED => 'Получен',
            self::CANCELLED => 'Отменён',
        };
    }
}
