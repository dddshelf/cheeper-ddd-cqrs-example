<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Author\Command\SignUpWithoutEvents;

use Cheeper\AllChapters\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommandHandler;
use Cheeper\Chapter4\Infrastructure\DomainModel\Author\InMemoryAuthorRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUpCommandHandlerTest extends TestCase
{
    private InMemoryAuthorRepository $authorRepository;

    protected function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
    }

    /** @test */
    public function givenAnAuthorNameThatAlreadyBelongsToAnExistingUserWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorAlreadyExists::class);
        $this->expectExceptionMessage('Author with name "johndoe" already exists');

        //snippet sign-up-handler-usage
        $signUpHandler = new SignUpCommandHandler($this->authorRepository);

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                'johndoe',
                'johndoe@example.com',
                'John Doe',
                'The usual profile example',
                'Madrid',
                'https://example.com/',
                (new DateTimeImmutable())->format('Y-m-d')
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
                (new DateTimeImmutable())->format('Y-m-d')
            )
        );
    }

    /** @test */
    public function givenValidAuthorDataWithOnlyMandatoryFieldsWhenSignUpThenAValidUserShouldBeCreated(): void
    {
        $signUpHandler = new SignUpCommandHandler($this->authorRepository);

        $userName = 'johndoe';
        $email = 'johndoe@example.com';

        $signUpHandler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                $userName,
                $email
            )
        );

        $actualAuthor = $this->authorRepository->ofUserName(UserName::pick($userName));
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
    public function givenValidAuthorDataWhenSignUpThenAValidUserShouldBeCreated(): void
    {
        $signUpHandler = new SignUpCommandHandler($this->authorRepository);

        $userName = 'johndoe';
        $email = 'johndoe@example.com';
        $name = 'John Doe';
        $biography = 'The usual profile example';
        $location = 'Madrid';
        $website = 'https://example.com/';
        $birthDate = (new DateTimeImmutable())->format('Y-m-d');

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

        $actualAuthor = $this->authorRepository->ofUserName(UserName::pick($userName));
        $this->assertNotNull($actualAuthor);
        $this->assertSame($userName, $actualAuthor->userName()->userName());
        $this->assertSame($email, $actualAuthor->email()->value());
        $this->assertSame($name, $actualAuthor->name());
        $this->assertSame($biography, $actualAuthor->biography());
        $this->assertSame($location, $actualAuthor->location());
        $this->assertSame($website, $actualAuthor->website()->toString());
        $this->assertSame($birthDate, $actualAuthor->birthDate()->date()->format('Y-m-d'));
    }

    /** @test */
    public function givenAnAuthorIdThatAlreadyBelongsToAnExistingUserWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorAlreadyExists::class);
//        $this->expectExceptionMessage('Author with name "johndoe" already exists');

        $signUpHandler = new SignUpCommandHandler($this->authorRepository);

        $authorId = '0c57e704-3982-4c90-9f3f-e00d5ea546ac';
        $signUpHandler(
            new SignUpCommand(
                $authorId,
                'johndoe',
                'johndoe@example.com',
                'John Doe',
                'The usual profile example',
                'Madrid',
                'https://example.com/',
                (new DateTimeImmutable())->format('Y-m-d')
            )
        );

        $signUpHandler(
            new SignUpCommand(
                $authorId,
                'new_johndoe',
                'johndoe@example.com',
                'John Doe',
                'The usual profile example',
                'Madrid',
                'https://example.com/',
                (new DateTimeImmutable())->format('Y-m-d')
            )
        );
    }
}
