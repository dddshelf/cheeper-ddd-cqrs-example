<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Author\Command\WithDomainEvents;

use Cheeper\AllChapters\DomainModel\Follow\FollowDoesAlreadyExistException;
use Cheeper\Chapter6\Application\Author\Command\FollowCommand;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter4\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter4\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter4\Application\EventBus;
use Cheeper\Chapter4\DomainModel\Author\Author;

//snippet follow-handler-with-event
final class FollowCommandHandler
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private FollowRepository $followRepository,
        // leanpub-start-insert
        private EventBus $eventBus
        // leanpub-end-insert
    ) {
    }

    public function __invoke(FollowCommand $command): void
    {
        $fromAuthorId = AuthorId::fromString($command->fromAuthorId());
        $toAuthorId = AuthorId::fromString($command->toAuthorId());

        $fromAuthor = $this->tryToFindTheAuthorOfId($fromAuthorId);
        $toAuthor = $this->tryToFindTheAuthorOfId($toAuthorId);

        $this->checkFollowDoesNotExistsYet($fromAuthorId, $toAuthorId);

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());
        $this->followRepository->add($follow);

        // leanpub-start-insert
        $this->eventBus->notifyAll($follow->domainEvents());
        // leanpub-end-insert
    }

    private function tryToFindTheAuthorOfId(AuthorId $authorId): Author
    {
        $author = $this->authorRepository->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $author;
    }

    protected function checkFollowDoesNotExistsYet(AuthorId $fromAuthorId, AuthorId $toAuthorId): void
    {
        $follow = $this->followRepository->ofFromAuthorIdAndToAuthorId($fromAuthorId, $toAuthorId);

        if (null !== $follow) {
            throw FollowDoesAlreadyExistException::withFromAuthorIdToAuthorId($fromAuthorId, $toAuthorId);
        }
    }
}
// end-snippet
