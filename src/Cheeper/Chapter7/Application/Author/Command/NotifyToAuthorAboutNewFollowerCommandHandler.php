<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter7\DomainModel\Notifier\Notifier;

final class NotifyToAuthorAboutNewFollowerCommandHandler
{
    public function __construct(
        private AuthorRepository $authors,
        private Notifier         $notifier
    ) {
    }

    public function __invoke(NotifyToAuthorAboutNewFollowerCommand $command): void
    {
        $authorId = AuthorId::fromString($command->toAuthorId());
        $author = $this->authors->ofId($authorId);
        $this->checkAuthorExist($author, $authorId);

        $this->notifier->notify($author);
    }

    private function checkAuthorExist(?Author $author, AuthorId $authorId): void
    {
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }
    }
}
