<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Application\Author\Command\SignUpWithEvents;

use Cheeper\AllChapters\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\BirthDate;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Author\Website;
use Cheeper\Chapter2\Hexagonal\DomainModel\AuthorRepository;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter6\Application\Event\EventBus;

//snippet sign-up-handler-with-events
final class SignUpCommandHandler
{
    public function __construct(
        private AuthorRepository $authors,
        private EventBus         $eventBus
    ) {
    }

    public function __invoke(SignUpCommand $command): void
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
