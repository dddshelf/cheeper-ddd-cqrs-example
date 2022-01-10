<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Command\Author;

use Cheeper\Chapter6\Application\Event\EventBus;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;

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

        $domainEvents = $author->domainEvents();
        foreach ($domainEvents as $k => $domainEvent)
        {
            $domainEvents[$k]->stampAsResponseTo($command);
        }

        $this->eventBus->notifyAll($domainEvents);
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
