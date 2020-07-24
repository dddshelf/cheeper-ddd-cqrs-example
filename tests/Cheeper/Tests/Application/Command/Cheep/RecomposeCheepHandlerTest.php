<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Cheep;

use Cheeper\Application\Command\Cheep\RecomposeCheep;
use Cheeper\Application\Command\Cheep\RecomposeCheepHandler;
use Cheeper\DomainModel\Cheep\CheepDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\Tests\Helper\SendsCommands;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

//snippet recompose-cheep-handler-test
final class RecomposeCheepHandlerTest extends TestCase
{
    use SendsCommands;

    /** @test */
    public function whenCheepDoesNotExistThenAnExceptionShouldBeThrown(): void
    {
        $cheepId = Uuid::uuid4()->toString();
        $this->expectException(CheepDoesNotExist::class);
        $this->expectExceptionMessage(sprintf("Cheep with ID %s does not exist", $cheepId));

        $this->recomposeCheep($cheepId, "new cheep message");
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

        $this->recomposeCheep($cheepId, "new cheep message");

        $cheep = $this->cheeps->ofId(CheepId::fromString($cheepId));
        $this->assertSame("new cheep message", $cheep->cheepMessage()->message());
    }

    private function recomposeCheep(string $cheepId, string $message): void
    {
        $recomposeCheepHandler = new RecomposeCheepHandler($this->cheeps);

        $recomposeCheepHandler(
            new RecomposeCheep(
                $cheepId,
                $message
            )
        );
    }
}
//end-snippet
