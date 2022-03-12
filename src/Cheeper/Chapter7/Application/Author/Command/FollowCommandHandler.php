<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\EventBus;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter7\DomainModel\Follow\FollowRepository;

final class FollowCommandHandler
{
    public function __construct(
        private AuthorRepository $authors,
        private FollowRepository $follows,
        private EventBus         $eventBus
    ) {
    }

    public function __invoke(FollowCommand $command): void
    {
        $fromAuthorId = AuthorId::fromString($command->fromAuthorId());
        $toAuthorId = AuthorId::fromString($command->toAuthorId());

        $fromAuthor = $this->tryToFindTheAuthorOfId($fromAuthorId);
        $toAuthor = $this->tryToFindTheAuthorOfId($toAuthorId);

        $follow = $this->follows->fromAuthorIdAndToAuthorId($fromAuthorId, $toAuthorId);

        if (null !== $follow) {
            return;
        }

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());
        $this->follows->add($follow);

        $domainEvents = $follow->domainEvents();
        $this->notifyEvents($command, $domainEvents);
    }

    private function tryToFindTheAuthorOfId(AuthorId $authorId): Author
    {
        $author = $this->authors->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $author;
    }

    private function notifyEvents(FollowCommand $command, array $domainEvents): void
    {
        $stamppedEvents = array_map(
            static fn ($event) => $event->stampAsResponseTo($command),
            $domainEvents
        );

        $this->eventBus->notifyAll($stamppedEvents);
    }
}
