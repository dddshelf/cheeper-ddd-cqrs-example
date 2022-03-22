<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\AuthorFollowed;

use Cheeper\Chapter7\Application\Author\Command\NotifyToAuthorAboutNewFollowerCommand;
use Cheeper\Chapter7\Application\CommandBus;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

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
