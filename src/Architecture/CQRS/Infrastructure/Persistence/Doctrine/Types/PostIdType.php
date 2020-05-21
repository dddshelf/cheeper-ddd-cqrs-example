<?php

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine\Types;

use Architecture\CQRS\Domain\PostId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PostIdType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param mixed $value
     * @return PostId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new PostId((string) $value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        /** @var PostId $value */
        return $value->id();
    }

    public function getName()
    {
        return 'post_id';
    }
}
