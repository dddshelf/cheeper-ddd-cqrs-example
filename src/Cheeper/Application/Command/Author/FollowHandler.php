<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\UserName;

final class FollowHandler
{
    public function __construct(
        private Authors $authors
    ) { }

    public function __invoke(Follow $command): void
    {
        $followeeUserName   = UserName::pick($command->followeeUsername());
        $followedUserName   = UserName::pick($command->followedUsername());

        $author = $this->authors->ofUserName($followeeUserName);

        if (null === $author) {
            throw AuthorDoesNotExist::withUserNameOf($followeeUserName);
        }

        $followed = $this->authors->ofUserName($followedUserName);

        if (null === $followed) {
            throw AuthorDoesNotExist::withUserNameOf($followedUserName);
        }

        $author->follow($followed->userId());

        $this->authors->save($author);
    }
}
