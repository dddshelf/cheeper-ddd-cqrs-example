<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Author\Command\WithoutDomainEvents;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter4\DomainModel\Author\Author;
use Cheeper\Chapter4\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter4\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter6\Application\Author\Command\FollowCommand;

//snippet follow-handler-without-event
final class FollowCommandHandler
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private FollowRepository $followRepository
    ) {
    }

    public function __invoke(FollowCommand $command): void
    {
        $fromAuthorId = AuthorId::fromString($command->fromAuthorId());
        $toAuthorId = AuthorId::fromString($command->toAuthorId());

        $fromAuthor = $this->tryToFindTheAuthorOfId($fromAuthorId);
        $toAuthor = $this->tryToFindTheAuthorOfId($toAuthorId);

        $follow = $this->followRepository->ofFromAuthorIdAndToAuthorId($fromAuthorId, $toAuthorId);
        if (null !== $follow) {
            return;
        }

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());
        $this->followRepository->add($follow);
    }

    private function tryToFindTheAuthorOfId(AuthorId $authorId): Author
    {
        $author = $this->authorRepository->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $author;
    }
}
// end-snippet
