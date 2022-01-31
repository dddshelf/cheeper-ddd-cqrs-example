<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Author\Command\SignUpWithoutEvents;

use Cheeper\AllChapters\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommandHandler;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUpCommandHandlerTest extends TestCase
{
    /** @test */
    public function givenAUserNameThatAlreadyBelongsToAnExistingUserWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorAlreadyExists::class);
        $this->expectExceptionMessage('Author with name "johndoe" already exists');

        $authors = new InMemoryAuthors();

        //snippet sign-up-handler-usage
        $signUpHandler = new SignUpCommandHandler($authors);

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
        //end-snippet

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
    public function givenValidUserDataWithOnlyMandatoryFieldsWhenSignUpThenAValidUserShouldBeCreated(): void
    {
        $authors = new InMemoryAuthors();

        $signUpHandler = new SignUpCommandHandler($authors);

        $userName = 'johndoe';
        $email = 'johndoe@example.com';

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                $userName,
                $email
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
    }

    /** @test */
    public function givenValidUserDataWhenSignUpThenAValidUserShouldBeCreated(): void
    {
        $authors = new InMemoryAuthors();

        $signUpHandler = new SignUpCommandHandler($authors);

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
    }
}
