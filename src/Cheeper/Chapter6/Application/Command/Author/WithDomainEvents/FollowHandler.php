<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Command\Author\WithDomainEvents;

use Cheeper\Chapter6\Application\Command\Author\Follow;
use Cheeper\Chapter6\Application\Command\EventBus;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Follow\Follows;

//snippet follow-handler-with-event
final class FollowHandler
{
    public function __construct(
        private Authors $authorsRepository,
        private Follows $followsRepository,
        private EventBus $eventBus
    ) { }

    public function __invoke(Follow $command): void
    {
        $fromAuthorId = AuthorId::fromString($command->fromAuthorId());
        $toAuthorId = AuthorId::fromString($command->toAuthorId());

        $fromAuthor = $this->tryToFindTheAuthorOfId($fromAuthorId);
        $toAuthor = $this->tryToFindTheAuthorOfId($toAuthorId);

        $follow = $this->followsRepository->ofFromAuthorIdAndToAuthorId($fromAuthorId, $toAuthorId);
        if (null !== $follow) {
            return;
        }

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());
        $this->followsRepository->save($follow);

        $this->eventBus->notify($follow->domainEvents());
    }

    private function tryToFindTheAuthorOfId(AuthorId $authorId): Author
    {
        $author = $this->authorsRepository->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $author;
    }
}
// end-snippet