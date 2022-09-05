<?php

declare(strict_types=1);

namespace Cheeper\Application\PostCheep;

use Cheeper\Application\EventBus;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PostCheepCommandHandler
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly CheepRepository  $cheepRepository,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(PostCheepCommand $command): void
    {
        $cheepId = CheepId::fromString($command->cheepId);
        $authorUsername = UserName::pick($command->username);
        $author = $this->authorRepository->ofUserName($authorUsername);

        if (null === $author) {
            throw AuthorDoesNotExist::withUserNameOf($authorUsername);
        }

        $cheep = Cheep::compose(
            $author->authorId(),
            $cheepId,
            CheepMessage::write($command->message)
        );

        $this->cheepRepository->add($cheep);
        $this->eventBus->publishAll($cheep->pullEvents());
    }
}
