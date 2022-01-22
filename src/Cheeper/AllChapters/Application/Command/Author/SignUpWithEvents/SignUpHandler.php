<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Application\Command\Author\SignUpWithEvents;

use Cheeper\AllChapters\Application\Command\Author\SignUp;
use Cheeper\Chapter6\Application\Event\EventBus;
use Cheeper\AllChapters\DomainModel\Author\Author;
use Cheeper\AllChapters\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Author\BirthDate;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Author\Website;

//snippet sign-up-handler-with-events
final class SignUpHandler
{
    public function __construct(
        private Authors $authors,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(SignUp $command): void
    {
        $userName = UserName::pick($command->userName());
        $authorId = AuthorId::fromString($command->authorId());
        $email = EmailAddress::from($command->email());

        $author = $this->authors->ofUserName($userName);
        $this->checkAuthorDoesNotAlreadyExistByUsername($author, $userName);

        $author = $this->authors->ofId($authorId);
        $this->checkAuthorDoesNotAlreadyExistById($author, $authorId);

        $inputWebsite = $command->website();
        $website = null !== $inputWebsite ? Website::fromString($inputWebsite) : null;

        $inputBirthDate = $command->birthDate();
        $birthDate  = null !== $inputBirthDate ? BirthDate::fromString($inputBirthDate) : null;

        $author = Author::signUp(
            $authorId,
            $userName,
            $email,
            $command->name(),
            $command->biography(),
            $command->location(),
            $website,
            $birthDate
        );

        $this->authors->add($author);
        $this->eventBus->notifyAll($author->domainEvents());
    }

    private function checkAuthorDoesNotAlreadyExistByUsername(?Author $author, UserName $userName): void
    {
        if (null !== $author) {
            throw AuthorAlreadyExists::withUserNameOf($userName);
        }
    }

    private function checkAuthorDoesNotAlreadyExistById(?Author $author, AuthorId $authorId): void
    {
        if (null !== $author) {
            throw AuthorAlreadyExists::withIdOf($authorId);
        }
    }
}
//end-snippet
