<?php

declare(strict_types=1);


namespace Cheeper\Tests\Helper;

use Cheeper\Application\Command\Author\Follow;
use Cheeper\Application\Command\Author\FollowHandler;
use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Author\SignUpHandler;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait SendsCommands
{
    private Authors $authors;

    /** @before */
    protected function makeUserRepository(): void
    {
        $this->authors = new InMemoryAuthors();
    }

    private function signUpAuthorWith(UuidInterface $authorId, string $userName, string $name, string $biography, string $location, string $website, DateTimeImmutable $birthDate): void
    {
        (new SignUpHandler(
            $this->authors
        ))(
            new SignUp(
                $authorId,
                $userName,
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
        (new FollowHandler($this->authors))(
            new Follow(Uuid::uuid4(), $followee, $followed)
        );
    }
}
