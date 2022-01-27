<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Cheep\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\Chapter7\Application\EventBus;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter7\DomainModel\Cheep\Cheep;
use Cheeper\Chapter7\DomainModel\Cheep\CheepRepository;

//snippet post-cheep-handler
final class PostCheepCommandHandler
{
    public function __construct(
        private AuthorRepository $authors,
        private CheepRepository  $cheeps,
        private EventBus         $eventBus
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
