<?php

declare(strict_types=1);

namespace Cheeper\Application\CountFollowers;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;

final class CountFollowersQueryHandler
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly FollowRepository $followRepository,
    ) {
    }

    public function __invoke(CountFollowersQuery $query): int
    {
        $this->tryToFindAuthor($query->authorId);

        return $this->followRepository->numberOfFollowersFor(
            AuthorId::fromString($query->authorId)
        );
    }

    /** @psalm-param non-empty-string $authorId */
    private function tryToFindAuthor(string $authorId): void
    {
        $id = AuthorId::fromString($authorId);
        $author = $this->authorRepository->ofId($id);

        $this->assertAuthorIsNotNull($author, $id);
    }

    /** @psalm-assert Author $author */
    private function assertAuthorIsNotNull(Author|null $author, AuthorId $id): void
    {
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($id);
        }
    }
}