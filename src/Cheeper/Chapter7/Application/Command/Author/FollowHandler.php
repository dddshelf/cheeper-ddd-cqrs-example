<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Command\Author;

use Cheeper\Chapter6\Application\Event\EventBus;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Follow\Follows;

final class FollowHandler
{
    public function __construct(
        private Authors  $authors,
        private Follows  $follows,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(Follow $command): void
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

        /**
         * By @carlos to @christian
         * TODO: To add the correlation id
         * and the cause id of the events
         * triggered by Aggregates is complicated
         * inside the Aggregates itself.
         * So, I believe there are some options:
         *
         * 1. Static registry/singleton with the
         *    current command
         * 2. Get all the events in the Handler
         *    and set cause and correlation id
         *    before notifiying to the EventBus
         */
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