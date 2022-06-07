<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\NewAuthorSigned;

use Cheeper\Chapter7\Application\Author\Command\WelcomeCommand;
use Cheeper\Chapter7\Application\CommandBus;

// We use NewAuthorSigned from chapter 8 so this handler does not
// handle the correct Domain Event, so the demo works as expected
use Cheeper\Chapter8\DomainModel\Author\NewAuthorSigned;

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
