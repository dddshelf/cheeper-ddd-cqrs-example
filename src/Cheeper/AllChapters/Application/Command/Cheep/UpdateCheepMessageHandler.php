<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Application\Command\Cheep;

use Cheeper\AllChapters\DomainModel\Cheep\CheepDoesNotExist;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\AllChapters\DomainModel\Cheep\Cheeps;

//snippet recompose-cheep-handler
final class UpdateCheepMessageHandler
{
    public function __construct(
        private Cheeps $cheeps
    ) {
    }

    public function __invoke(UpdateCheepMessage $message): void
    {
        $cheepId = CheepId::fromString($message->cheepId());
        $cheepMessage = CheepMessage::write($message->message());

        $cheep = $this->cheeps->ofId($cheepId);

        if (null === $cheep) {
            throw CheepDoesNotExist::withIdOf($cheepId);
        }

        $cheep->recomposeWith($cheepMessage);
    }
}
//end-snippet
