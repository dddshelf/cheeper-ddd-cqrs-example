<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\DomainModel\Cheep\CheepRepository;

final class CheepApplicationService
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly CheepRepository  $cheepRepository,
    ) {
    }

    public function postCheep(string $username, string $message): Cheep
    {
        $authorUsername = UserName::pick($username);
        $author = $this->authorRepository->ofUserName($authorUsername);

        if (null === $author) {
            throw AuthorDoesNotExist::withUserNameOf($authorUsername);
        }

        $cheep = Cheep::compose(
            $author->authorId(),
            CheepId::nextIdentity(),
            CheepMessage::write($message)
        );

        $this->cheepRepository->add($cheep);

        return $cheep;
    }

    public function timelineFrom(string $authorId, int $offset, int $size): array
    {
        $author = $this->authorRepository->ofId(AuthorId::fromString($authorId));

        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf(AuthorId::fromString($authorId));
        }

        return $this->cheepRepository->ofFollowingPeopleOf($author, $offset, $size);
    }

    public function getCheep(string $id): ?Cheep
    {
        return $this->cheepRepository->ofId(CheepId::fromString($id));
    }
}
