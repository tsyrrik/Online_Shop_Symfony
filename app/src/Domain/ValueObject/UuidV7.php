<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class UuidV7
{
    private string $uuid;

    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::uuid7()->toString();
        if (!Uuid::isValid(uuid: $this->uuid)) {
            throw new InvalidArgumentException(message: 'Invalid UUID');
        }
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $other): bool
    {
        return $this->uuid === $other->uuid;
    }
}
