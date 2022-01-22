<?php

declare(strict_types=1);


namespace Cheeper\Tests\Helper;

use Cheeper\AllChapters\Application\Command\Author\Follow;
use Cheeper\AllChapters\Application\Command\Author\FollowHandler;
use Cheeper\AllChapters\Application\Command\Author\SignUp;
use Cheeper\AllChapters\Application\Command\Author\SignUpHandler;
use Cheeper\AllChapters\Application\Command\Cheep\PostCheep;
use Cheeper\AllChapters\Application\Command\Cheep\PostCheepHandler;
use Cheeper\Chapter6\Application\Event\EventBus;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Cheep\Cheeps;
use Cheeper\AllChapters\DomainModel\DomainEvent;
use Cheeper\AllChapters\DomainModel\Follow\Follows;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryCheeps;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryFollows;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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

    private function signUpAuthorWith(string $authorId, string $userName, string $email, string $name, string $biography, string $location, string $website, string $birthDate): void
    {
        (new SignUpHandler(
            $this->authors
        ))(
            new SignUp(
                $authorId,
                $userName,
                $email,
                $name,
                $biography,
                $location,
                $website,
                $birthDate
            )
        );
    }

    private function followAuthor(string $followee, string $followed): void
    {
        (new FollowHandler($this->authors, $this->follows))(
            Follow::anAuthor($followed, $followee)
        );
    }

    //snippet post-new-cheep-tests
    private function postNewCheep(string $authorId, string $cheepId, string $message): void
    {
        (new PostCheepHandler($this->authors, $this->cheeps, $this->eventBus))(
            PostCheep::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }
    //end-snippet
}
