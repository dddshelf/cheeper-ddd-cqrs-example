<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Cheep;

use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\Tests\Helper\SendsCommands;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

//snippet post-cheep-handler-test
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
    public function throwsExceptionWhenAuthorIdIsNotUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep("1", Uuid::uuid4()->toString(), 'test');
    }

    /** @test */
    public function throwsExceptionWhenCheepIdIsNotUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(Uuid::uuid4()->toString(), "1", 'test');
    }

    /** @test */
    public function throwsExceptionWhenCheepMessageIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), "");
    }

    /** @test */
    public function cheepIsSavedSuccessfully(): void
    {
        $authorId = Uuid::uuid4()->toString();

        $this->signUpAuthorWith(
            $authorId,
            'test',
            'test@email.com',
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
//end-snippet
