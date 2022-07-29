<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;

final class FollowApplicationService
{
    public function __construct(
        private readonly FollowRepository $followRepository,
        private readonly AuthorRepository $authorRepository,
    ) {
    }

    public function followTo(string $fromAuthorId, string $toAuthorId): void
    {
        $fromAuthor = $this->tryToFindAuthor($fromAuthorId);
        $toAuthor = $this->tryToFindAuthor($toAuthorId);

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());

        $this->followRepository->add($follow);
    }

    public function countFollowersOf(string $authorId): int
    {
        $authorId = AuthorId::fromString($authorId);

        if (null === $this->authorRepository->ofId($authorId)) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $this->followRepository->numberOfFollowersFor($authorId);
    }

    private function tryToFindAuthor(string $authorId): Author
    {
        $fromAuthor = $this->authorRepository->ofId(AuthorId::fromString($authorId));

        if (null === $fromAuthor) {
            throw AuthorDoesNotExist::withAuthorIdOf(AuthorId::fromString($authorId));
        }

        return $fromAuthor;
    }
}