<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Author\SignUpWithEvents;

use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\DomainModel\Author\NewAuthorSigned;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Author\SignUpWithEvents\SignUpHandler;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUpHandlerTest extends TestCase
{
    /** @test */
    public function givenAUserNameThatAlreadyBelongsToAnExistingUserWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorAlreadyExists::class);
        $this->expectExceptionMessage('Author with name "johndoe" already exists');

        $authors = new InMemoryAuthors();
        $eventBus = new InMemoryEventBus();

        $signUpHandler = new SignUpHandler(
            $authors,
            $eventBus
        );

        $signUpHandler(
            new SignUp(
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
            new SignUp(
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
        $authors = new InMemoryAuthors();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpHandler(
            $authors,
            $eventBus
        );

        $userName = 'johndoe';
        $email = 'johndoe@example.com';
        $signUpHandler(
            new SignUp(
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
        $authors = new InMemoryAuthors();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpHandler(
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
            new SignUp(
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

        $authors = new InMemoryAuthors();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpHandler(
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
            new SignUp(
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

        $authors = new InMemoryAuthors();
        $eventBus = new InMemoryEventBus();
        $signUpHandler = new SignUpHandler(
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
            new SignUp(
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
