<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Cheep\Command;

use Cheeper\AllChapters\DomainModel\Cheep\CheepDoesNotExist;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommandHandler;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommand;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommandHandler;
use Cheeper\Chapter4\Application\Cheep\Command\UpdateCheepMessageCommand;
use Cheeper\Chapter4\Application\Cheep\Command\UpdateCheepMessageCommandHandler;
use Cheeper\Chapter4\Infrastructure\DomainModel\Author\InMemoryAuthorRepository;
use Cheeper\Chapter4\Infrastructure\DomainModel\Cheep\InMemoryCheepRepository;
use Cheeper\Chapter4\Infrastructure\Application\InMemoryEventBus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

//snippet recompose-cheep-handler-test
final class UpdateCheepMessageHandlerTest extends TestCase
{
    private InMemoryCheepRepository $cheepRepository;
    private InMemoryAuthorRepository $authorRepository;
    private InMemoryEventBus $eventBus;

    protected function setUp(): void
    {
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->eventBus = new InMemoryEventBus();
    }

    /** @test */
    public function whenCheepDoesNotExistThenAnExceptionShouldBeThrown(): void
    {
        $cheepId = Uuid::uuid4()->toString();
        $this->expectException(CheepDoesNotExist::class);
        $this->expectExceptionMessage(sprintf("Cheep with ID %s does not exist", $cheepId));

        $this->updateCheepMessage($cheepId, "new cheep message");
    }

    /** @test */
    public function shouldRecomposeCheepSuccessfully(): void
    {
        $cheepId = Uuid::uuid4()->toString();
        $authorId = Uuid::uuid4()->toString();

        $this->signUpAuthorWith(
            $authorId,
            'test',
            'test@email.com',
            'test',
            'test',
            'test',
            'https://test.com',
            '1983-01-07'
        );

        $this->postNewCheep($authorId, $cheepId, 'Cheep message');
        $this->updateCheepMessage($cheepId, "new cheep message");

        $cheep = $this->cheepRepository->ofId(CheepId::fromString($cheepId));
        $this->assertSame("new cheep message", $cheep->message()->message());
    }

    private function postNewCheep(string $authorId, string $cheepId, string $message): void
    {
        (new PostCheepCommandHandler(
            $this->authorRepository,
            $this->cheepRepository,
            $this->eventBus
        ))(
            PostCheepCommand::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }

    private function signUpAuthorWith(string $authorId, string $userName, string $email, string $name, string $biography, string $location, string $website, string $birthDate): void
    {
        (new SignUpCommandHandler(
            $this->authorRepository
        ))(
            new SignUpCommand(
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

    private function updateCheepMessage(string $cheepId, string $message): void
    {
        $updateCheepMessageHandler = new UpdateCheepMessageCommandHandler(
            $this->cheepRepository
        );

        $updateCheepMessageHandler(
            new UpdateCheepMessageCommand(
                $cheepId,
                $message
            )
        );
    }
}
//end-snippet
