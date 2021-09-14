<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use RuntimeException;

final class AuthorAlreadyExists extends RuntimeException
{
    public function __construct(string $authorName)
    {
        parent::__construct(
            sprintf('Author with name "%s" already exists', $authorName)
        );
    }

    public static function withUserNameOf(UserName $userName): self
    {
        return new self($userName->userName());
    }
}
