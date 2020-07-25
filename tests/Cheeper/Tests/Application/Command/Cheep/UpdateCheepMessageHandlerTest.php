<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Cheep;

use Cheeper\Application\Command\Cheep\UpdateCheepMessage;
use Cheeper\Application\Command\Cheep\UpdateCheepMessageHandler;
use Cheeper\DomainModel\Cheep\CheepDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\Tests\Helper\SendsCommands;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

//snippet recompose-cheep-handler-test
final class UpdateCheepMessageHandlerTest extends TestCase
{
    use SendsCommands;

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

        $cheep = $this->cheeps->ofId(CheepId::fromString($cheepId));
        $this->assertSame("new cheep message", $cheep->cheepMessage()->message());
    }

    private function updateCheepMessage(string $cheepId, string $message): void
    {
        $updateCheepMessageHandler = new UpdateCheepMessageHandler($this->cheeps);

        $updateCheepMessageHandler(
            new UpdateCheepMessage(
                $cheepId,
                $message
            )
        );
    }
}
//end-snippet
