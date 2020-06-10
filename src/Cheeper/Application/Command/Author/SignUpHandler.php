<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;

//snippet sign-up-handler
final class SignUpHandler
{
    private Authors $authors;

    public function __construct(Authors $authors)
    {
        $this->authors = $authors;
    }

    public function __invoke(SignUp $command): void
    {
        $userName = UserName::pick($command->userName());

        $author = $this->authors->ofUserName($userName);

        if (null !== $author) {
            throw AuthorAlreadyExists::withUserNameOf($userName);
        }

        $authorId   = AuthorId::fromString($command->authorId());
        $website    = $command->website() ? new Website($command->website()) : null;
        $birthDate  = $command->birthDate() ? new BirthDate($command->birthDate()) : null;

        $this->authors->save(
            Author::signUp(
                $authorId,
                $userName,
                $command->name(),
                $command->biography(),
                $command->location(),
                $website,
                $birthDate
            )
        );
    }
}
//end-snippet
