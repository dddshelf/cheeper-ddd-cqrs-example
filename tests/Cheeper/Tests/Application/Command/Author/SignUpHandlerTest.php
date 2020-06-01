<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Author;

use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\Tests\Helper\SendsCommands;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUpHandlerTest extends TestCase
{
    use SendsCommands;

    /** @test */
    public function givenAUserNameThatAlreadyBelongsToAnExistingUserWhenSignUpThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorAlreadyExists::class);

        $this->signUpAuthorWith(
            Uuid::uuid4()->toString(),
            'test',
            'test',
            'test',
            'test',
            'https://google.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $this->signUpAuthorWith(
            Uuid::uuid4()->toString(),
            'test',
            'test',
            'test',
            'test',
            'https://google.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );
    }

    /** @test */
    public function givenValidUserDataWhenSignUpThenAValidUserShouldBeCreated(): void
    {
        $this->signUpAuthorWith(
            Uuid::uuid4()->toString(),
            'test',
            'test',
            'test',
            'test',
            'https://google.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $this->assertNotNull(
            $this->authors->ofUserName(UserName::pick('test'))
        );
    }
}
