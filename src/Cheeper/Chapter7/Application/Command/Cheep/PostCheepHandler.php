<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Command\Cheep;

use Cheeper\Chapter6\Application\Event\EventBus;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\Authors;
use Cheeper\Chapter7\DomainModel\Cheep\Cheep;
use Cheeper\Chapter7\DomainModel\Cheep\Cheeps;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;

//snippet post-cheep-handler
final class PostCheepHandler
{
    public function __construct(
        private Authors $authors,
        private Cheeps $cheeps,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(PostCheep $command): void
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