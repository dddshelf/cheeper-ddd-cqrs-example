<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\AuthorRepository;

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
        return Author:: ::reconstitute(
            $this->eventStore->getEventsFor($authorId->id())
        );
    }

    public function ofUserName(UserName $userName): ?Author
    {
        // TODO: Implement ofUserName() method.
    }

    public function add(Author $author): void
    {
        // TODO: Implement add() method.
    }
}
//end-snippet