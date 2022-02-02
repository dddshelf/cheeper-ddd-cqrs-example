<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Author\Command\SignUpWithEvents;

use Cheeper\AllChapters\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\AllChapters\DomainModel\Author\NewAuthorSigned;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithEvents\SignUpCommandHandler;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUpCommandHandlerTest extends TestCase
{
    /** @test */
    public function givenAUserNameThatAlreadyBelongsToAnExistingUserWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorAlreadyExists::class);
        $this->expectExceptionMessage('Author with name "johndoe" already exists');

        $authors = new InMemoryAuthorRepository();
        $eventBus = new InMemoryEventBus();

        $signUpHandler = new SignUpCommandHandler(
            $authors,
            $eventBus
        );

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                'johndoe',
                'johndoe@example.com',
                'John Doe',
                'The usual profile example',
                'Madrid',
                'https://example.com/',
                (new \DateTimeImmutable())->format('Y-m-d')
            )
        );

        $eventBus->reset();

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                'johndoe',
                'johndoe@example.com',
                'John Doe',
                'The usual profile example',
                'Madrid',
                'https://example.com/',
                (new \DateTimeImmutable())->format('Y-m-d')
            )
        );
    }

    /** @test */
    public function givenValidUserDataWhenSignUpWithOnlyMandatoryFieldsThenAValidUserShouldBeCreated(): void
    {
        $authors = new InMemoryAuthorRepository();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpCommandHandler(
            $authors,
            $eventBus
        );

        $userName = 'johndoe';
        $email = 'johndoe@example.com';
        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                $userName,
                $email,
            )
        );

        $actualAuthor = $authors->ofUserName(UserName::pick($userName));
        $this->assertNotNull($actualAuthor);
        $this->assertSame($userName, $actualAuthor->userName()->userName());
        $this->assertSame($email, $actualAuthor->email()->value());
        $this->assertNull($actualAuthor->name());
        $this->assertNull($actualAuthor->biography());
        $this->assertNull($actualAuthor->location());
        $this->assertNull($actualAuthor->website());
        $this->assertNull($actualAuthor->birthDate());

        $events = $eventBus->events();
        $this->assertCount(1, $events);
        $this->assertSame(NewAuthorSigned::class, $events[0]::class);
    }

    /** @test */
    public function givenValidUserDataWhenSignUpWithAllFieldsThenAValidUserShouldBeCreated(): void
    {
        $authors = new InMemoryAuthorRepository();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpCommandHandler(
            $authors,
            $eventBus
        );

        $userName = 'johndoe';
        $email = 'johndoe@example.com';
        $name = 'John Doe';
        $biography = 'The usual profile example';
        $location = 'Madrid';
        $website = 'https://example.com/';
        $birthDate = (new \DateTimeImmutable())->format('Y-m-d');

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                $userName,
                $email,
                $name,
                $biography,
                $location,
                $website,
                $birthDate
            )
        );

        $actualAuthor = $authors->ofUserName(UserName::pick($userName));
        $this->assertNotNull($actualAuthor);
        $this->assertSame($userName, $actualAuthor->userName()->userName());
        $this->assertSame($email, $actualAuthor->email()->value());
        $this->assertSame($name, $actualAuthor->name());
        $this->assertSame($biography, $actualAuthor->biography());
        $this->assertSame($location, $actualAuthor->location());
        $this->assertSame($website, $actualAuthor->website()->toString());
        $this->assertSame($birthDate, $actualAuthor->birthDate()->date()->format('Y-m-d'));

        $events = $eventBus->events();
        $this->assertCount(1, $events);
        $this->assertSame(NewAuthorSigned::class, $events[0]::class);
    }

    /** @test */
    public function givenInvalidEmailUserDataWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email not-a-valid-email');

        $authors = new InMemoryAuthorRepository();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpCommandHandler(
            $authors,
            $eventBus
        );

        $userName = 'johndoe';
        $email = 'not-a-valid-email';
        $name = 'John Doe';
        $biography = 'The usual profile example';
        $location = 'Madrid';
        $website = 'https://example.com/';
        $birthDate = (new \DateTimeImmutable())->format('Y-m-d');

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
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

    /** @test */
    public function givenInvalidWebsiteUserDataWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL given');

        $authors = new InMemoryAuthorRepository();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpCommandHandler(
            $authors,
            $eventBus
        );

        $userName = 'johndoe';
        $email = 'carlos.buenosvinos@gmail.com';
        $name = 'John Doe';
        $biography = 'The usual profile example';
        $location = 'Madrid';
        $website = 'not-a-valid-website';
        $birthDate = (new \DateTimeImmutable())->format('Y-m-d');

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
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
}
