<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Author;

use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Author\Author as AuthorChapter6;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\FollowId;
use DateTimeImmutable;

class Author extends AuthorChapter6
{
    protected function __construct(
        protected string             $authorId,
        protected string             $userName,
        protected string             $email,
        protected ?string            $name = null,
        protected ?string            $biography = null,
        protected ?string            $location = null,
        protected ?string            $website = null,
        protected ?DateTimeImmutable $birthDate = null,
    ) {
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);

        $this->notifyDomainEvent(
            NewAuthorSigned::fromAuthor($this)
        );
    }

    public function followAuthorId(AuthorId $toFollow): Follow
    {
        return Follow::fromAuthorToAuthor(
            followId: FollowId::nextIdentity(),
            fromAuthorId: $this->authorId(),
            toAuthorId: $toFollow
        );
    }
}
