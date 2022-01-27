<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\EventBus;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\Authors;
use Cheeper\Chapter7\DomainModel\Follow\Follows;

final class FollowCommandHandler
{
    public function __construct(
        private Authors  $authors,
        private Follows  $follows,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(FollowCommand $command): void
    {
        $fromAuthorId = AuthorId::fromString($command->fromAuthorId());
        $toAuthorId = AuthorId::fromString($command->toAuthorId());

        $fromAuthor = $this->tryToFindTheAuthorOfId($fromAuthorId);
        $toAuthor = $this->tryToFindTheAuthorOfId($toAuthorId);

        $follow = $this->follows->ofFromAuthorIdAndToAuthorId($fromAuthorId, $toAuthorId);
        if (null !== $follow) {
            return;
        }

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());
        $this->follows->add($follow);

        $followEvent = $follow->domainEvents()[0];
        $followEvent->stampAsResponseTo($command);

        $this->eventBus->notifyAll([$followEvent]);
    }

    private function tryToFindTheAuthorOfId(AuthorId $authorId): Author
    {
        $author = $this->authors->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $author;
    }
}
