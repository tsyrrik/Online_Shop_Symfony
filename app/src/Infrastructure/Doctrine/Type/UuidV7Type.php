<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\UuidV7;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UuidV7Type extends Type
{
    public const NAME = 'uuid_v7';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL(column: $column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UuidV7
    {
        return $value === null ? null : new UuidV7(uuid: $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : $value->toString();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
