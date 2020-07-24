<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Author;

use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Author\SignUpHandler;
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

        //snippet sign-up-handler-usage
        $signUpHandler = new SignUpHandler($authors);

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
        //end-snippet

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
    public function givenValidUserDataWhenSignUpThenAValidUserShouldBeCreated(): void
    {
        $authors = new InMemoryAuthors();

        $signUpHandler = new SignUpHandler($authors);

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

        $this->assertNotNull(
            $authors->ofUserName(UserName::pick('johndoe'))
        );
    }
}
