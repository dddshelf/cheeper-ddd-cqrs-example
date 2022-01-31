<?php

declare(strict_types=1);


namespace Cheeper\Tests\Helper;

use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommand;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommandHandler;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Cheep\Cheeps;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryCheeps;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryFollows;

trait SendsCommands
{
    private Authors $authors;
    private Cheeps $cheeps;
    private InMemoryFollows $follows;
    private InMemoryEventBus $eventBus;

    /** @before */
    final protected function makeUserRepository(): void
    {
        $this->authors = new InMemoryAuthors();
    }

    //snippet setup-cheeps-repository
    /** @before */
    final protected function makeCheepsRepository(): void
    {
        $this->cheeps = new InMemoryCheeps();
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
