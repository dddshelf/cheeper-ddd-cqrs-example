<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Application\Author\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorRepository;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Follow\Follow as FollowAggregate;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\AllChapters\DomainModel\Follow\Follows;

final class FollowCommandHandler
{
    public function __construct(
        private AuthorRepository $authors,
        private Follows          $follows,
    ) {
    }

    public function __invoke(FollowCommand $command): void
    {
        $followeeUserName = UserName::pick($command->followeeUsername());
        $followedUserName = UserName::pick($command->followedUsername());

        $author = $this->authors->ofUserName($followeeUserName);

        if (null === $author) {
            throw AuthorDoesNotExist::withUserNameOf($followeeUserName);
        }

        $followed = $this->authors->ofUserName($followedUserName);

        if (null === $followed) {
            throw AuthorDoesNotExist::withUserNameOf($followedUserName);
        }

        $follow = FollowAggregate::fromAuthorToAuthor(
            FollowId::nextIdentity(),
            $author->authorId(),
            $followed->authorId()
        );

        $this->follows->add($follow);
    }
}
