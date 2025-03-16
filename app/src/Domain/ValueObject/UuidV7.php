<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Ramsey\Uuid\UuidInterface;

final class UuidV7
{
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
