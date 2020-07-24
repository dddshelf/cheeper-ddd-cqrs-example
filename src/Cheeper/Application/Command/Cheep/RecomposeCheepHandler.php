<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Cheep;

use Cheeper\DomainModel\Cheep\CheepDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\DomainModel\Cheep\Cheeps;

//snippet recompose-cheep-handler
final class RecomposeCheepHandler
{
    private Cheeps $cheeps;

    public function __construct(Cheeps $cheeps)
    {
        $this->cheeps = $cheeps;
    }

    public function __invoke(RecomposeCheep $message): void
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
