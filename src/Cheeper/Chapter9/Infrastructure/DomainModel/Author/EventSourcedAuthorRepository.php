<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter9\DomainModel\Author\Author;
use Cheeper\Chapter9\DomainModel\Author\AuthorRepository;
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

    /*
    public function ofUserName(UserName $userName): ?Author
    {
        // When doing Event Sourcing, Repositories
        // responsibility is reduced to hold
        // a finder by id, and add.
        // Other finder methods become
        // a Projection
    }
    */

    public function add(Author $author): void
    {
        $this->eventStore->append(
            new EventStream(
                $author->authorId()->toString(),
                $author->domainEvents()
            )
        );
    }
}
//end-snippet