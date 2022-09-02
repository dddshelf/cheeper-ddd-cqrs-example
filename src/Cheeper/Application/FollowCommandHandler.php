<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;

final class FollowCommandHandler
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly FollowRepository $followRepository,
    )
    {
    }

    public function __invoke(FollowCommand $command): void
    {
        $fromAuthorId = $command->fromAuthorId;
        $toAuthorId = $command->toAuthorId;

        $fromAuthor = $this->tryToFindAuthor($fromAuthorId);
        $toAuthor = $this->tryToFindAuthor($toAuthorId);

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());

        $this->followRepository->add($follow);
    }

    /** @psalm-param non-empty-string $authorId */
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