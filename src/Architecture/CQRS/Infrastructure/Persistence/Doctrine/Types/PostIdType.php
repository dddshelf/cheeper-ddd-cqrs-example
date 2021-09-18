<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine\Types;

use Architecture\CQRS\Domain\PostId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class PostIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): PostId
    {
        return new PostId((string) $value);
    }

    /**
     * @param PostId $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->id();
    }

    public function getName(): string
    {
        return 'post_id';
    }
}
