<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Application\Cheep\Command;

use Cheeper\AllChapters\DomainModel\Author\Author;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Cheep\Cheep;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\AllChapters\DomainModel\Cheep\Cheeps;
use Cheeper\Chapter6\Application\Event\EventBus;

//snippet post-cheep-handler
final class PostCheepCommandHandler
{
    public function __construct(
        private Authors $authors,
        private Cheeps $cheeps,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(PostCheepCommand $command): void
    {
        $authorId = AuthorId::fromString($command->authorId());
        $cheepId = CheepId::fromString($command->cheepId());
        $message = CheepMessage::write($command->message());

        $author = $this->authors->ofId($authorId);
        $this->checkAuthorExists($author, $authorId);

        $cheep = Cheep::compose($authorId, $cheepId, $message);
        $this->cheeps->add($cheep);

        $this->eventBus->notifyAll($cheep->domainEvents());
    }

    private function checkAuthorExists(?Author $author, AuthorId $authorId): void
    {
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }
    }
}
//end-snippet
