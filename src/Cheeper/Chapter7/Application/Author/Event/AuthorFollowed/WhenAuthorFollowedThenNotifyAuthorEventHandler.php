<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\AuthorFollowed;

use Cheeper\Chapter7\Application\Author\Command\NotifyToAuthorAboutNewFollowerCommand;
use Cheeper\Chapter7\Application\CommandBus;

// We use AuthorFollowed from chapter 8 so this handler does not
// handle the correct Domain Event, so the demo works as expected
use Cheeper\Chapter8\DomainModel\Follow\AuthorFollowed;

//snippet snippet
final class WhenAuthorFollowedThenNotifyAuthorEventHandler
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function __invoke(AuthorFollowed $event): void
    {
        $this->commandBus->handle(
            NotifyToAuthorAboutNewFollowerCommand::fromArray([
                'from_author_id' => $event->fromAuthorId(),
                'to_author_id' => $event->toAuthorId(),
            ])
        );
    }
}
//end-snippet
