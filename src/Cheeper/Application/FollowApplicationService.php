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

    /**
     * @psalm-param non-empty-string $fromAuthorId
     * @psalm-param non-empty-string $toAuthorId
     */
    public function followTo(string $fromAuthorId, string $toAuthorId): void
    {
        $fromAuthor = $this->tryToFindAuthor($fromAuthorId);
        $toAuthor = $this->tryToFindAuthor($toAuthorId);

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());

        $this->followRepository->add($follow);
    }

    /**
     * @psalm-param non-empty-string $authorId
     */
    public function countFollowersOf(string $authorId): int
    {
        $this->tryToFindAuthor($authorId);

        return $this->followRepository->numberOfFollowersFor(
            AuthorId::fromString($authorId)
        );
    }

    /**
     * @psalm-param non-empty-string $authorId
     */
    private function tryToFindAuthor(string $authorId): Author
    {
        $id = AuthorId::fromString($authorId);
        $author = $this->authorRepository->ofId($id);

        $this->assertAuthorIsNotNull($author, $id);

        return $author;
    }

    /** @psalm-assert Author $author */
    private function assertAuthorIsNotNull(Author|null $author, AuthorId $id): void
    {
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($id);
        }
    }
}
