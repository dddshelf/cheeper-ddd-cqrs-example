<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter7\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter9\DomainModel\Author\Author;
use Cheeper\Chapter9\DomainModel\EventStore;
use Cheeper\Chapter9\DomainModel\EventStream;

//snippet code
class EventSourcedAuthorRepository implements AuthorRepository
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function ofId(AuthorId $authorId): Author
    {
        return Author::reconstitute(
            $this->eventStore->getEventsFor($authorId->id())
        );
    }

    public function ofUserName(UserName $userName): ?Author
    {
        // It's not possible to look
        // for an entity by the username
        // except that we consume
    }

    public function add(Author $author): void
    {
        $this->eventStore->append(
            newEventStream:: $author->domainEvents()
        );
    }
}
//end-snippet