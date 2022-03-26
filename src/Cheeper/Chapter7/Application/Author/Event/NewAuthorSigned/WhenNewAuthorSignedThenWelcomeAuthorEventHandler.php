<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\NewAuthorSigned;

use Cheeper\Chapter7\Application\Author\Command\WelcomeCommand;
use Cheeper\Chapter7\Application\CommandBus;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;

//snippet snippet
final class WhenNewAuthorSignedThenWelcomeAuthorEventHandler
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function __invoke(NewAuthorSigned $event): void
    {
        $this->commandBus->handle(
            WelcomeCommand::ofAuthorId($event->authorId())
        );
    }
}
//end-snippet
