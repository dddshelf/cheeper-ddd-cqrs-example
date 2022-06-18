<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Author\Event;

use Cheeper\Chapter6\Application\ProjectionBus;
use Cheeper\Chapter7\Application\Author\Command\NotifyToAuthorAboutNewFollowerCommand;
use Cheeper\Chapter7\Application\Author\Projection\IncrementCountFollowersProjection;
use Cheeper\Chapter7\Application\CommandBus;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet snippet
final class AuthorFollowedEventHandler
{
    public function __construct(
        private ProjectionBus $projectionBus,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(AuthorFollowed $event): void
    {
        $this->projectionBus->project(
            IncrementCountFollowersProjection::ofAuthor(
                $event->toAuthorId()
            )
        );

        // Other actions, like notifying the Author
        // about the new Follower can be added here.
        // Alternatively, a more scalable design is
        // to create an Event Handler for each of
        // action that has to happen per
        // AuthorFollowed Domain Event.
        // @see: WhenAuthorFollowedThenIncrementFollowersProjectionEventHandler
        // @see: WhenAuthorFollowedThenWelcomeAuthorEventHandler

        $this->commandBus->handle(
            NotifyToAuthorAboutNewFollowerCommand::fromArray([
                'from_author_id' => $event->fromAuthorId(),
                'to_author_id' => $event->toAuthorId(),
            ])
        );
    }
}
//end-snippet
