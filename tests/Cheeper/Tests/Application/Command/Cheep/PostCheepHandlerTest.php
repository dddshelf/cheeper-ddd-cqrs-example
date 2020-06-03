<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Cheep;

use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\Tests\Helper\SendsCommands;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

final class PostCheepHandlerTest extends TestCase
{
    use SendsCommands;

    /** @test */
    public function throwsExceptionWhenAuthorDoesNotExist(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->postNewCheep(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), 'test');
    }

    /** @test */
    public function throwsExceptionWhenAuthorIdIsNotString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(1, Uuid::uuid4()->toString(), 'test');
    }

    /** @test */
    public function throwsExceptionWhenCheepIdIsNotString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(Uuid::uuid4()->toString(), 1, 'test');
    }

    /** @test */
    public function throwsExceptionWhenCheepMessageIsNotString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), 0);
    }

    /** @test */
    public function cheepIsSavedSuccessfully(): void
    {
        $authorId = Uuid::uuid4()->toString();

        $this->signUpAuthorWith(
            $authorId,
            'test',
            'test',
            'test',
            'test',
            'http://google.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $cheepId = Uuid::uuid4()->toString();

        $this->postNewCheep($authorId, $cheepId, 'test');

        $cheep = $this->cheeps->ofId(CheepId::fromString($cheepId));
        $this->assertNotNull($cheep);
    }
}
