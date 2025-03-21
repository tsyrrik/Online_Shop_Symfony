<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

abstract class BaseDTO
{
    public function toArray(): array
    {
        return get_object_vars(object: $this);
    }

    public function toJson(): string|false
    {
        return json_encode(value: $this->toArray());
    }
}
