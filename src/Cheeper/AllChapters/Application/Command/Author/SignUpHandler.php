<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Application\Command\Author;

use Cheeper\AllChapters\DomainModel\Author\Author;
use Cheeper\AllChapters\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Author\BirthDate;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Author\Website;

//snippet sign-up-handler
final class SignUpHandler
{
    public function __construct(
        private Authors $authors
    ) {
    }

    public function __invoke(SignUp $command): void
    {
        $userName = UserName::pick($command->userName());
        $author = $this->authors->ofUserName($userName);

        if (null !== $author) {
            throw AuthorAlreadyExists::withUserNameOf($userName);
        }

        $authorId   = AuthorId::fromString($command->authorId());
        $email      = new EmailAddress($command->email());

        $inputWebsite = $command->website();
        $website    = null !== $inputWebsite ? new Website($inputWebsite) : null;

        $inputBirthDate = $command->birthDate();
        $birthDate  = null !== $inputBirthDate ? new BirthDate($inputBirthDate) : null;

        $this->authors->add(
            Author::signUp(
                $authorId,
                $userName,
                $email,
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