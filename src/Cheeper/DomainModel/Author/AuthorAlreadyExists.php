<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use RuntimeException;

final class AuthorAlreadyExists extends RuntimeException
{
    /**
     * @psalm-param non-empty-string $authorName
     * @psalm-param non-empty-string $fieldName
     */
    private function __construct(string $authorName, string $fieldName)
    {
        parent::__construct(
            sprintf('Author with %s "%s" already exists', $fieldName, $authorName)
        );
    }

    public static function withUserNameOf(UserName $userName): self
    {
        return new self($userName->userName, 'name');
    }

    public static function withIdOf(AuthorId $authorId): self
    {
        return new self($authorId->id, 'id');
    }
}
