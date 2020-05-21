<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use RuntimeException;

final class AuthorDoesNotExist extends RuntimeException
{
    private function __construct(string $authorName)
    {
        parent::__construct(
            sprintf('Author "%s" does not exist', $authorName)
        );
    }

    public static function withUserNameOf(UserName $userName): self
    {
        return new self($userName->userName());
    }

    public static function withAuthorIdOf(AuthorId $authorId): self
    {
        return new self($authorId->toString());
    }
}
