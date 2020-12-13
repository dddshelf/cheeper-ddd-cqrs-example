<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Cheep;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\DomainModel\Cheep\Cheeps;

//snippet post-cheep-handler
final class PostCheepHandler
{
    public function __construct(
        private Authors $authors,
        private Cheeps $cheeps,
    ) { }

    public function __invoke(PostCheep $command): void
    {
        $authorId = AuthorId::fromString($command->authorId());
        $cheepId = CheepId::fromString($command->cheepId());
        $message = CheepMessage::write($command->message());

        $author = $this->authors->ofId($authorId);
        $this->checkAuthorExists($author, $authorId);

        $this->cheeps->add(
            Cheep::compose($authorId, $cheepId, $message)
        );
    }

    private function checkAuthorExists(?Author $author, AuthorId $authorId): void
    {
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }
    }
}
//end-snippet
