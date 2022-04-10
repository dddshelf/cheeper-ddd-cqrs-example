<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter9\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter9\DomainModel\Author\Author;
use Cheeper\Chapter9\DomainModel\EventStore;
use Cheeper\Chapter9\DomainModel\EventStream;

//snippet code
final class EventSourcedAuthorRepository implements AuthorRepository
{
    public function __construct(
        private EventStore $eventStore
    ) {
    }

    public function ofId(AuthorId $authorId): Author|null
    {
        $eventStream = $this->eventStore->getEventsFor($authorId->id());

        if ($eventStream->isEmpty()) {
            return null;
        }

        return Author::reconstitute($eventStream);
    }

    public function ofUserName(UserName $userName): ?Author
    {
        // It's not possible to look
        // for an entity by the username
        // except that we consume
    }

    public function add(Author $author): void
    {
        $eventStream = new EventStream($author->authorId(), $author->domainEvents());

        $this->eventStore->append($eventStream);
    }
}
//end-snippet