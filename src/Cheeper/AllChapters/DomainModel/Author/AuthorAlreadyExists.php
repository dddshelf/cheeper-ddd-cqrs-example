<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Author;

use RuntimeException;

final class AuthorAlreadyExists extends RuntimeException
{
    private function __construct(string $authorName, string $fieldName)
    {
        parent::__construct(
            sprintf('Author with %s "%s" already exists', $fieldName, $authorName)
        );
    }

    public static function withUserNameOf(UserName $userName): self
    {
        return new self($userName->userName(), 'name');
    }

    public static function withIdOf(AuthorId $authorId): self
    {
        return new self($authorId->id(), 'id');
    }
}
