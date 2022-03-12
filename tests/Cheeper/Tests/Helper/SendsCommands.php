<?php

declare(strict_types=1);


namespace Cheeper\Tests\Helper;

use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryCheepRepository;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryFollows;
use Cheeper\Chapter2\Hexagonal\DomainModel\AuthorRepository;
use Cheeper\Chapter2\Hexagonal\DomainModel\CheepRepository;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommand;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommandHandler;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;

trait SendsCommands
{
    private AuthorRepository $authors;
    private CheepRepository $cheeps;
    private InMemoryFollows $follows;
    private InMemoryEventBus $eventBus;

    /** @before */
    final protected function makeUserRepository(): void
    {
        $this->authors = new InMemoryAuthorRepository();
    }

    //snippet setup-cheeps-repository
    /** @before */
    final protected function makeCheepsRepository(): void
    {
        $this->cheeps = new InMemoryCheepRepository();
    }
    //end-snippet

    /** @before  */
    final protected function makeFollowsRepository(): void
    {
        $this->follows = new InMemoryFollows();
    }

    /** @before */
    final protected function makeEventBus(): void
    {
        $this->eventBus = new InMemoryEventBus();
    }

    //snippet post-new-cheep-tests
    private function postNewCheep(string $authorId, string $cheepId, string $message): void
    {
        (new PostCheepCommandHandler($this->authors, $this->cheeps, $this->eventBus))(
            PostCheepCommand::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }
    //end-snippet
}
