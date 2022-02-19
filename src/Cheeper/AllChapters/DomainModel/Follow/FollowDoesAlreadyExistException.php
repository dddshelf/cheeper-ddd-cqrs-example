<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use RuntimeException;

final class FollowDoesAlreadyExistException extends RuntimeException
{
    private function __construct(string $fromAuthorId, string $toAuthorId)
    {
        parent::__construct(
            sprintf('Follow from author "%s" to author "%s" already exists', $fromAuthorId, $toAuthorId)
        );
    }

    public static function withFromAuthorIdToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): self
    {
        return new self($fromAuthorId->toString(), $toAuthorId->toString());
    }
}
